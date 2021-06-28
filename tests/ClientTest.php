<?php namespace Farzai\Tests;

use Farzai\ThaiPost\Auth\ApiToken;
use Farzai\ThaiPost\Client\Client;
use Farzai\ThaiPost\Client\GuzzleClient;

class ClientTest extends TestCase
{

    public function test_shouldBeGuzzleHttpClientByDefault()
    {
        $client = new Client(new ApiToken(""));

        $this->assertInstanceOf(GuzzleClient::class, $client->createClient());
    }
}