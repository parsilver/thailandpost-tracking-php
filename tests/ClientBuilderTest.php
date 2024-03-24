<?php

use Farzai\ThaiPost\Client;
use Farzai\ThaiPost\ClientBuilder;

it('should throw error if api key is empty', function () {
    $client = ClientBuilder::create()->build();
})->throws(\InvalidArgumentException::class, 'Please specify token');

it('can create a client', function () {
    $client = ClientBuilder::create()
        ->setCredential('token')
        ->build();

    expect($client)->toBeInstanceOf(Client::class);
});
