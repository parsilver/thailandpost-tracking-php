<?php

use Farzai\Support\Carbon;
use Farzai\ThaiPost\Authorizer;
use Farzai\ThaiPost\ClientBuilder;
use Farzai\ThaiPost\Contracts\StorageRepositoryInterface;
use Farzai\ThaiPost\Repositories\AccessTokenRepository;
use Farzai\ThaiPost\Tests\MockHttpClient;

it('should call generate token success', function () {
    $httpClient = MockHttpClient::new()
        ->addSequence(
            MockHttpClient::response(200, json_encode([
                'expire' => $expires = Carbon::now()->addHour()->format(Carbon::ATOM),
                'token' => 'this-is-valid-token',
            ]), ['Content-Type' => 'application/json'])
        );

    $storage = $this->createMock(StorageRepositoryInterface::class);
    $storage->method('get')->with('access-token:api')->willReturn(json_encode([
        'token' => 'this-is-expired-token',
        'expires_at' => Carbon::now()->subHour()->format(Carbon::ATOM),
    ]));
    $storage->method('create')->with('access-token:api', json_encode([
        'token' => 'this-is-valid-token',
        'expires_at' => $expires,
    ]));

    $accessTokenRepository = new AccessTokenRepository('access-token:api', $storage);

    $client = ClientBuilder::create()
        ->setCredential('token')
        ->setHttpClient($httpClient)
        ->setStorage($storage)
        ->build();

    $authorizer = new Authorizer($client, $accessTokenRepository);

    $accessToken = $authorizer->retrieveAccessTokenForApi();

    expect($accessToken->getToken())->toBe('this-is-valid-token');
    expect($accessToken->expiresAt()->format(Carbon::ATOM))->toBe($expires);
    expect($accessToken->isExpired())->toBeFalse();
});
