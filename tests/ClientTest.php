<?php

namespace Farzai\Tests;

use Farzai\ThaiPost\Client;

class ClientTest extends TestCase
{
    public function test_should_throw_error_if_config_is_empty()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Please specify api_key');

        // Try to create a client with invalid config.
        $client = new Client([]);
    }

    public function test_should_throw_error_if_api_key_is_empty()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Please specify api_key');

        // Try to create a client with invalid config.
        $client = new Client(['api_key' => '']);
    }

    public function test_should_success_if_config_is_valid()
    {
        // Try to create a client with valid config.
        $client = new Client(['api_key' => '123456789']);

        $this->assertInstanceOf(Client::class, $client);
    }

    public function test_should_return_rest_api_client()
    {
        $client = new Client(['api_key' => '123456789']);

        $this->assertInstanceOf(Client::class, $client);
        $this->assertInstanceOf(\GuzzleHttp\Client::class, $client->restApi());
    }

    public function test_should_return_webhook_api_client()
    {
        $client = new Client(['api_key' => '123456789']);

        $this->assertInstanceOf(Client::class, $client);
        $this->assertInstanceOf(\GuzzleHttp\Client::class, $client->webhook());
    }
}
