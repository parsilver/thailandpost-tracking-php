<?php

use Farzai\ThaiPost\ClientBuilder;
use Farzai\ThaiPost\Endpoints\ApiEndpoint;
use Farzai\ThaiPost\Exceptions\UnauthorizedException;
use Psr\Http\Client\ClientInterface as PsrClientInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Http\Message\StreamInterface as PsrStreamInterface;

it('should throw exception when invalid api-token', function () {
    $htmlBody = '<html><body><h1>Unauthorized</h1></body></html>';

    $psrStream = $this->createMock(PsrStreamInterface::class);
    $psrStream->method('getContents')->willReturn($htmlBody);

    $psrResponse = $this->createMock(PsrResponseInterface::class);
    $psrResponse->method('getStatusCode')->willReturn(401);
    $psrResponse->method('getHeaderLine')->willReturn('text/html');
    $psrResponse->method('getBody')->willReturn($psrStream);

    $httpClient = $this->createMock(PsrClientInterface::class);
    $httpClient->method('sendRequest')->willReturn($psrResponse);

    $client = ClientBuilder::create()
        ->setCredential('invalid-token')
        ->setHttpClient($httpClient)
        ->build();

    $apiEndpoint = new ApiEndpoint($client);

    $response = $apiEndpoint->generateAccessToken();
})->throws(UnauthorizedException::class, 'Unauthorized');

it('should return access token', function () {
    $jsonBody = json_encode([
        'expire' => '2024-04-01 22:13:42+07:00',
        'token' => 'this-is-valid-token',
    ]);

    $psrStream = $this->createMock(PsrStreamInterface::class);
    $psrStream->method('getContents')->willReturn($jsonBody);

    $psrResponse = $this->createMock(PsrResponseInterface::class);
    $psrResponse->method('getStatusCode')->willReturn(200);
    $psrResponse->method('getHeaderLine')->willReturn('application/json');
    $psrResponse->method('getBody')->willReturn($psrStream);

    $httpClient = $this->createMock(PsrClientInterface::class);
    $httpClient->method('sendRequest')->willReturn($psrResponse);

    $client = ClientBuilder::create()
        ->setCredential('valid-token')
        ->setHttpClient($httpClient)
        ->build();

    $apiEndpoint = new ApiEndpoint($client);

    $response = $apiEndpoint->generateAccessToken();

    expect($response->getStatusCode())->toBe(200);
    expect($response->getHeaderLine('Content-Type'))->toBe('application/json');
    expect($response->getBody()->getContents())->toBe($jsonBody);
});
