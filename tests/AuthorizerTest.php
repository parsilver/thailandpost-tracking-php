<?php

use Farzai\Support\Carbon;
use Farzai\ThaiPost\AccessTokenEntity;
use Farzai\ThaiPost\Authorizer;
use Farzai\ThaiPost\ClientBuilder;
use Farzai\ThaiPost\Contracts\EndpointVisitor;
use Farzai\ThaiPost\Tests\MockHttpClient;

it('should be instance of EndpointVisitor', function () {
    $authorizer = new Authorizer();

    expect($authorizer)->toBeInstanceOf(EndpointVisitor::class);
});

it('should call generate token success', function () {
    $authorizer = new Authorizer();
    $jsonBody = json_encode([
        'expire' => $expires = Carbon::now()->addHour()->format(Carbon::ATOM),
        'token' => 'this-is-valid-token',
    ]);

    $httpClient = MockHttpClient::new()
        ->addSequence(
            MockHttpClient::response(200, $jsonBody, ['Content-Type' => 'application/json'])
        );

    $client = ClientBuilder::create()
        ->setCredential('token')
        ->setHttpClient($httpClient)
        ->build();

    $accessToken = $authorizer->generateAccessTokenForApiEndpoint($client);

    expect($accessToken)->toBeInstanceOf(AccessTokenEntity::class);

    expect($accessToken->getToken())->toBe('this-is-valid-token');
    expect($accessToken->expiresAt()->format(Carbon::ATOM))->toBe($expires);
    expect($accessToken->isExpired())->toBeFalse();
});
