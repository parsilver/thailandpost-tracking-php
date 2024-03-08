<?php

namespace Farzai\ThaiPost\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Psr\Http\Client\ClientInterface as PsrClientInterface;
use Psr\Http\Message\RequestInterface as PsrRequestInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Http\Message\StreamInterface as PsrStreamInterface;

class MockHttpClient extends PHPUnitTestCase implements PsrClientInterface
{
    /**
     * The sequence of responses.
     *
     * @var array<int, PsrResponseInterface>
     */
    private array $sequence = [];


    /**
     * Create a response with the given status code and contents.
     */
    public static function response(
        int $statusCode,
        array|string $contents,
        array $headers = []
    ): PsrResponseInterface {
        $client = static::new();

        if (is_array($contents)) {
            $contents = json_encode($contents);
        }

        return $client->createResponse($statusCode, $contents, $headers);
    }

    /**
     * Create a new mock http client instance.
     */
    public static function new(): self
    {
        return new static('mock-http-client');
    }

    /**
     * Add a sequence of responses.
     *
     * @param  PsrResponseInterface|callable<PsrResponseInterface>  ...$responses
     */
    public function addSequence(
        PsrResponseInterface|callable ...$responses
    ): self {
        foreach ($responses as $response) {
            if (is_callable($response)) {
                $response = $response($this);
            }

            $this->sequence[] = $response;
        }

        return $this;
    }

    /**
     * Send a PSR-7 request and return a PSR-7 response.
     */
    public function sendRequest(
        PsrRequestInterface $request
    ): PsrResponseInterface {
        return array_shift($this->sequence);
    }

    /**
     * Create a stream with the given contents.
     */
    public function createStream(string $contents): PsrStreamInterface
    {
        $stream = $this->createMock(PsrStreamInterface::class);
        $stream->method('getContents')->willReturn($contents);

        return $stream;
    }

    /**
     * Create a response with the given status code and contents.
     */
    public function createResponse(
        int $statusCode,
        string $contents,
        array $headers = []
    ): PsrResponseInterface {
        $stream = $this->createStream($contents);

        $response = $this->createMock(PsrResponseInterface::class);
        $response->method('getStatusCode')->willReturn($statusCode);
        $response->method('getBody')->willReturn($stream);
        $response->method('getHeaders')->willReturn($headers);

        return $response;
    }

}
