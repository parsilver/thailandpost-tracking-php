<?php

use Farzai\Support\Carbon;
use Farzai\ThaiPost\ClientBuilder;
use Farzai\ThaiPost\Contracts\StorageRepositoryInterface;
use Farzai\ThaiPost\Endpoints\ApiEndpoint;
use Farzai\ThaiPost\Tests\MockHttpClient;
use Psr\Http\Client\ClientInterface as PsrClientInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Http\Message\StreamInterface as PsrStreamInterface;

it('should track by barcodes success', function () {
    $expectJsonStringResponse = json_encode(['response' => 'success']);

    $storage = $this->createMock(StorageRepositoryInterface::class);
    $storage->method('has')->with('access-token:api')->willReturn(true);
    $storage->method('get')->with('access-token:api')->willReturn(
        json_encode([
            'token' => 'valid-token',
            'expires_at' => Carbon::now()
                ->addHour()
                ->format(Carbon::ATOM),
        ])
    );

    $psrStream = $this->createMock(PsrStreamInterface::class);
    $psrStream->method('getContents')->willReturn($expectJsonStringResponse);

    $psrResponse = $this->createMock(PsrResponseInterface::class);
    $psrResponse->method('getStatusCode')->willReturn(200);
    $psrResponse->method('getHeaderLine')->willReturn('application/json');
    $psrResponse->method('getBody')->willReturn($psrStream);

    $httpClient = $this->createMock(PsrClientInterface::class);
    $httpClient->method('sendRequest')->willReturn($psrResponse);

    $client = ClientBuilder::create()
        ->setCredential('valid-token')
        ->setHttpClient($httpClient)
        ->setStorage($storage)
        ->build();

    $api = new ApiEndpoint($client);

    $response = $api->getItemsByBarcodes([
        'barcode' => ['1234567890', '1234567890'],
    ]);

    expect($response->getStatusCode())->toBe(200);
    expect($response->getHeaderLine('content-type'))->toBe('application/json');
    expect($response->getBody()->getContents())->toBe(
        $expectJsonStringResponse
    );
});

it('should refresh access token if token is expired', function () {
    $httpClient = MockHttpClient::new()
        ->addSequence(
            MockHttpClient::response(
                200,
                json_encode([
                    'token' => 'valid-token',
                    'expire' => Carbon::now()
                        ->addHour()
                        ->format(Carbon::ATOM),
                ]),
                ['Content-Type' => 'application/json'],
            )
        )
        ->addSequence(
            MockHttpClient::response(
                200,
                json_encode(['response' => 'success']),
                ['Content-Type' => 'application/json'],
            ),
        );

    $storage = $this->createMock(StorageRepositoryInterface::class);
    $storage->method('has')->with('access-token:api')->willReturn(true);
    $storage->method('get')->with('access-token:api')->willReturn(
        json_encode([
            'token' => 'expired-token',
            'expires_at' => Carbon::now()
                ->subHour()
                ->format(Carbon::ATOM),
        ])
    );

    $storage
        ->expects($this->once())
        ->method('create')
        ->with(
            'access-token:api',
            json_encode([
                'token' => 'valid-token',
                'expires_at' => Carbon::now()
                    ->addHour()
                    ->format(Carbon::ATOM),
            ])
        );

    $client = ClientBuilder::create()
        ->setCredential('api-token')
        ->setHttpClient($httpClient)
        ->setStorage($storage)
        ->build();

    $api = new ApiEndpoint($client);

    $response = $api->getItemsByBarcodes([
        'barcode' => ['1234567890', '1234567890'],
    ]);

    expect($response->getStatusCode())->toBe(200);
    expect($response->getBody()->getContents())->toBe(
        json_encode(['response' => 'success'])
    );
});
