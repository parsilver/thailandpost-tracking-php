<?php

use Farzai\ThaiPost\ClientBuilder;
use Farzai\ThaiPost\Endpoints\WebhookEndpoint;

it('can create a new webhook endpoint instance', function () {
    $client = ClientBuilder::create()->setCredential('token')->build();

    $endpoint = new WebhookEndpoint($client);

    expect($endpoint)->toBeInstanceOf(WebhookEndpoint::class);

    $transport = $endpoint->getClient()->getTransport();

    expect($transport->getUri())->toBe('https://trackwebhook.thailandpost.co.th');
    expect($transport->getHeaders())->toBe([]);
});
