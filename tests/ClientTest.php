<?php

namespace Farzai\Tests;

use Farzai\ThaiPost\Client;
use Farzai\ThaiPost\ClientBuilder;

it('should success if config is valid', function () {
    $client = ClientBuilder::create()
        ->setCredential('token')
        ->build();

    expect($client)->toBeInstanceOf(Client::class);
});

it('should set logger success', function () {
    $logger = $this->createMock(\Psr\Log\LoggerInterface::class);

    $client = ClientBuilder::create()
        ->setCredential('token')
        ->setLogger($logger)
        ->build();

    expect($logger)->toBe($client->getLogger());
});

it('should set http client success', function () {
    $httpClient = $this->createMock(\Psr\Http\Client\ClientInterface::class);

    $client = ClientBuilder::create()
        ->setCredential('token')
        ->setHttpClient($httpClient)
        ->build();

    expect($httpClient)->toBe($client->getTransport()->getPsrClient());
});
