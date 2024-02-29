<?php

use Farzai\ThaiPost\Client;
use Farzai\ThaiPost\ClientBuilder;
use Farzai\ThaiPost\PendingRequest;

it('can get pending request', function () {
    $client = ClientBuilder::create()
        ->setCredential('token')
        ->build();

    $pendingRequest = new PendingRequest(
        $client,
        'POST',
        'https://trackapi.thailandpost.co.th/post/api/v1/track',
        [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ],
    );

    expect($pendingRequest)->toBeInstanceOf(PendingRequest::class);

    expect($pendingRequest->client)->toBeInstanceOf(Client::class);
    expect($pendingRequest->method)->toBe('POST');
    expect($pendingRequest->path)->toBe('https://trackapi.thailandpost.co.th/post/api/v1/track');
    expect($pendingRequest->options)->toBe([
        'headers' => [
            'Content-Type' => 'application/json',
        ],
    ]);
});

it('can set method', function () {
    $client = ClientBuilder::create()
        ->setCredential('token')
        ->build();

    $pendingRequest = new PendingRequest(
        $client,
        'POST',
        'https://trackapi.thailandpost.co.th/post/api/v1/track',
        [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ],
    );

    $pendingRequest->method('GET');

    expect($pendingRequest->method)->toBe('GET');
});

it('can set path', function () {
    $client = ClientBuilder::create()
        ->setCredential('token')
        ->build();

    $pendingRequest = new PendingRequest(
        $client,
        'POST',
        'https://trackapi.thailandpost.co.th/post/api/v1/track',
        [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ],
    );

    $pendingRequest->path('https://trackapi.thailandpost.co.th/post/api/v1/track/123456');

    expect($pendingRequest->path)->toBe('https://trackapi.thailandpost.co.th/post/api/v1/track/123456');
});

it('can set query', function () {
    $client = ClientBuilder::create()
        ->setCredential('token')
        ->build();

    $pendingRequest = new PendingRequest(
        $client,
        'POST',
        'https://trackapi.thailandpost.co.th/post/api/v1/track',
        [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ],
    );

    $pendingRequest->withQuery(['item' => '123456']);

    expect($pendingRequest->options['query'])->toBe(['item' => '123456']);
});

it('can set headers', function () {
    $client = ClientBuilder::create()
        ->setCredential('token')
        ->build();

    $pendingRequest = new PendingRequest(
        $client,
        'POST',
        'https://trackapi.thailandpost.co.th/post/api/v1/track',
        [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ],
    );

    $pendingRequest->withHeaders(['Authorization' => 'Bearer token']);

    expect($pendingRequest->options['headers'])->toBe([
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer token',
    ]);
});
