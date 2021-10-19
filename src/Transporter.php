<?php

namespace Farzai\ThaiPost;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Transporter implements ClientInterface
{

    /**
     * @var array
     */
    private $headers = [];

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function setHeader(string $name, string $value)
    {
        $this->headers[$name] = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        foreach ($this->headers as $name => $value) {
            if (! $request->hasHeader($name)) {
                $request = $request->withHeader($name, $value);
            }
        }

        return $this->client->sendRequest($request);
    }
}