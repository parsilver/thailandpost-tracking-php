<?php

use Farzai\ThaiPost\ClientBuilder;
use Farzai\ThaiPost\Endpoints\ApiEndpoint;

it("can create a new api endpoint instance", function () {
    $client = ClientBuilder::create()->setCredential("token")->build();

    $apiEndpoint = new ApiEndpoint($client);

    expect($apiEndpoint)->toBeInstanceOf(ApiEndpoint::class);

    $transport = $apiEndpoint->getClient()->getTransport();

    expect($transport->getUri())->toBe("https://trackapi.thailandpost.co.th");
    expect($transport->getHeaders())->toBe([]);
});
