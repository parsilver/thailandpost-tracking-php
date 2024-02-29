<?php

use Farzai\Support\Carbon;
use Farzai\ThaiPost\AccessTokenEntity;
use Farzai\ThaiPost\Contracts\AccessTokenRepositoryInterface;
use Farzai\ThaiPost\Contracts\StorageRepositoryInterface;
use Farzai\ThaiPost\Repositories\AccessTokenRepository;

it('should save access token to storage successfully', function () {
    $data = [
        'token' => 'Thisistoken',
        'expires_at' => Carbon::now()->addHours(1)->toDateTimeImmutable(),
    ];

    $storage = $this->createMock(StorageRepositoryInterface::class);
    $storage->method('create')->with('access-token', json_encode([
        'token' => $data['token'],
        'expires_at' => $data['expires_at']->format(Carbon::ATOM),
    ]));

    $accessTokenRepository = new AccessTokenRepository($storage);

    $accessTokenRepository->saveToken(
        new AccessTokenEntity($data['token'], $data['expires_at'])
    );

    expect($accessTokenRepository)->toBeInstanceOf(AccessTokenRepositoryInterface::class);
});

it('should get access token from storage failed', function () {
    $storage = $this->createMock(StorageRepositoryInterface::class);
    $storage->method('has')->with('access-token')->willReturn(false);

    $accessTokenRepository = new AccessTokenRepository($storage);

    $accessToken = $accessTokenRepository->getToken();
})->throws(\Farzai\ThaiPost\Exceptions\AccessTokenException::class);

it('should get access token from storage successfully', function () {
    $data = [
        'token' => 'Thisistoken',
        'expires_at' => Carbon::now()->addHours(1)->toDateTimeImmutable(),
    ];

    $storage = $this->createMock(StorageRepositoryInterface::class);
    $storage->method('has')->with('access-token')->willReturn(true);
    $storage->method('get')->with('access-token')->willReturn(json_encode([
        'token' => $data['token'],
        'expires_at' => $data['expires_at']->format(Carbon::ATOM),
    ]));

    $accessTokenRepository = new AccessTokenRepository($storage);

    $accessToken = $accessTokenRepository->getToken();

    expect($accessToken)->toBeInstanceOf(AccessTokenEntity::class);
    expect($accessToken->getToken())->toBe($data['token']);
    expect($accessToken->expiresAt()->format(Carbon::ATOM))->toBe($data['expires_at']->format(Carbon::ATOM));
});

it('should remove access token from storage successfully', function () {
    $storage = $this->createMock(StorageRepositoryInterface::class);
    $storage->method('delete')->with('access-token');

    $accessTokenRepository = new AccessTokenRepository($storage);

    $accessTokenRepository->forget();

    expect($accessTokenRepository)->toBeInstanceOf(AccessTokenRepositoryInterface::class);
});
