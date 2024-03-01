<?php

use Farzai\ThaiPost\ClientBuilder;
use Farzai\ThaiPost\Endpoints\ApiEndpoint;
use Psr\Http\Client\ClientInterface as PsrClientInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Http\Message\StreamInterface as PsrStreamInterface;

it("cannot track barcodes when invalid token", function () {
    $expectJsonStringResponse = json_encode([
        "message" => "Unauthorized",
        "status" => 401,
    ]);

    $psrStream = $this->createMock(PsrStreamInterface::class);
    $psrStream->method("getContents")->willReturn($expectJsonStringResponse);

    $psrResponse = $this->createMock(PsrResponseInterface::class);
    $psrResponse->method("getStatusCode")->willReturn(200);
    $psrResponse->method("getHeaderLine")->willReturn("application/json");
    $psrResponse->method("getBody")->willReturn($psrStream);

    $httpClient = $this->createMock(PsrClientInterface::class);
    $httpClient->method("sendRequest")->willReturn($psrResponse);

    $client = ClientBuilder::create()
        ->setCredential("invalid-token")
        ->setHttpClient($httpClient)
        ->build();

    $apiEndpoint = new ApiEndpoint($client);

    $response = $apiEndpoint->trackByBarcodes([
        "barcode" => ["EF123456789TH"],
    ]);

    expect($response->getStatusCode())->toBe(200);
    expect($response->getHeaderLine("Content-Type"))->toBe("application/json");
    expect($response->getBody()->getContents())->toBe(
        $expectJsonStringResponse
    );
});
