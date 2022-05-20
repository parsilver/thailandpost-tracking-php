<?php

namespace Farzai\Tests;

use Farzai\ThaiPost\Transporter;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class TransporterTest extends TestCase
{


    public function test_should_add_header_success()
    {
        $transporter = new Transporter($this->createMock(ClientInterface::class));
        $transporter->setHeader('name', 'value');

        $this->assertEquals(['name' => 'value'], $transporter->getHeaders());

        $transporter->setHeader('name', 'value2');

        $this->assertEquals(['name' => 'value2'], $transporter->getHeaders());
    }


    public function test_should_send_request_success()
    {
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $client = $this->createMock(ClientInterface::class);
        $client->expects($this->once())
            ->method('sendRequest')
            ->with($request)
            ->willReturn($response);

        $transporter = new Transporter($client);

        $this->assertSame($response, $transporter->sendRequest($request));
    }
}
