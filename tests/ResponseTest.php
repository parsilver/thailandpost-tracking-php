<?php

namespace Farzai\Tests;

use Farzai\ThaiPost\Response\Response as ThaiPostResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ResponseTest extends TestCase
{

    public function test_should_response_with_empty_data()
    {
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn('[]');

        $baseResponse = $this->createMock(ResponseInterface::class);
        $baseResponse->method('getStatusCode')->willReturn(200);
        $baseResponse->method('getBody')->willReturn($stream);

        $response = new ThaiPostResponse($baseResponse);

        $this->assertTrue($response->isOk());
        $this->assertEquals([], $response->json());
    }

    public function test_should_response_with_data()
    {
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn('{"foo": "bar"}');

        $baseResponse = $this->createMock(ResponseInterface::class);
        $baseResponse->method('getStatusCode')->willReturn(200);
        $baseResponse->method('getBody')->willReturn($stream);

        $response = new ThaiPostResponse($baseResponse);

        $this->assertTrue($response->isOk());
        $this->assertEquals(['foo' => 'bar'], $response->json());
    }

    public function test_should_response_with_data_and_key()
    {
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn('{"foo": "bar"}');

        $baseResponse = $this->createMock(ResponseInterface::class);
        $baseResponse->method('getStatusCode')->willReturn(200);
        $baseResponse->method('getBody')->willReturn($stream);

        $response = new ThaiPostResponse($baseResponse);

        $this->assertTrue($response->isOk());
        $this->assertEquals('bar', $response->json('foo'));
    }

    public function test_should_not_ok_if_fails()
    {
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn('{"foo": "bar"}');

        $baseResponse = $this->createMock(ResponseInterface::class);
        $baseResponse->method('getStatusCode')->willReturn(400);
        $baseResponse->method('getBody')->willReturn($stream);

        $response = new ThaiPostResponse($baseResponse);

        $this->assertFalse($response->isOk());

        $this->assertEquals(['foo' => 'bar'], $response->json());
    }
}
