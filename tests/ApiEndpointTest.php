<?php

use Farzai\ThaiPost\ClientBuilder;
use Farzai\ThaiPost\Endpoints\ApiEndpoint;

it('can create a new api endpoint instance', function () {
    $client = ClientBuilder::create()->setCredential('token')->build();

    $apiEndpoint = new ApiEndpoint($client);

    expect($apiEndpoint)->toBeInstanceOf(ApiEndpoint::class);

    $transport = $apiEndpoint->getClient()->getTransport();

    expect($transport->getUri())->toBe('https://trackapi.thailandpost.co.th');
    expect($transport->getHeaders())->toBe([]);
});

it('can get items by barcodes', function () {
    $psrStream = $this->createMock(\Psr\Http\Message\StreamInterface::class);
    $psrStream->method('getContents')->willReturn(
        json_encode([
            'message' => 'Unauthorized',
            'status' => 401,
        ])
    );

    $psrResponse = $this->createMock(
        \Psr\Http\Message\ResponseInterface::class
    );
    $psrResponse->method('getStatusCode')->willReturn(200);
    $psrResponse->method('getHeaderLine')->willReturn('application/json');
    $psrResponse->method('getBody')->willReturn($psrStream);

    $httpClient = $this->createMock(\Psr\Http\Client\ClientInterface::class);
    $httpClient->method('sendRequest')->willReturn($psrResponse);

    $client = ClientBuilder::create()
        ->setCredential('token')
        ->setHttpClient($httpClient)
        ->build();

    $apiEndpoint = new ApiEndpoint($client);

    $response = $apiEndpoint->getItemsByBarcodes([
        'barcode' => ['EF123456789TH'],
    ]);

    expect($response->getStatusCode())->toBe(200);
    expect($response->getHeaderLine('Content-Type'))->toBe('application/json');
    expect($response->getBody()->getContents())->toBe(
        '{"message":"Unauthorized","status":401}'
    );
});
