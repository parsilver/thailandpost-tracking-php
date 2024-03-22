<?php

use Farzai\ThaiPost\Contracts\AccessTokenEntityInterface;
use Farzai\ThaiPost\AccessTokenEntity;
use Farzai\Support\Carbon;

it('should create access token entity successfully', function () {
    $data = [
        'token' => 'Thisistoken',
        'expires_at' => Carbon::now()->addHours(1)->toDateTimeImmutable(),
    ];

    $accessToken = new AccessTokenEntity($data['token'], $data['expires_at']);

    expect($accessToken)->toBeInstanceOf(AccessTokenEntityInterface::class);
    expect($accessToken->getToken())->toBe($data['token']);
    expect($accessToken->expiresAt()->format(Carbon::ATOM))->toBe($data['expires_at']->format(Carbon::ATOM));
});

it('should create access token entity from array successfully', function () {
    $data = [
        'token' => 'Thisistoken',
        'expires_at' => Carbon::now()->addHours(1)->toDateTimeImmutable(),
    ];

    $accessToken = AccessTokenEntity::fromArray([
        'token' => $data['token'],
        'expires_at' => $data['expires_at']->format(Carbon::ATOM),
    ]);

    expect($accessToken)->toBeInstanceOf(AccessTokenEntityInterface::class);
    expect($accessToken->getToken())->toBe($data['token']);
    expect($accessToken->expiresAt()->format(Carbon::ATOM))->toBe($data['expires_at']->format(Carbon::ATOM));
});


it('should throw exception if access token entity data is invalid', function () {
    AccessTokenEntity::fromArray([
        'token' => 'Thisistoken',
    ]);
})->throws(\InvalidArgumentException::class, 'The access token entity data is invalid.');

it('should throw exception if expires_at is invalid', function () {
    AccessTokenEntity::fromArray([
        'token' => 'Thisistoken',
        'expires_at' => 'invalid-date',
    ]);
})->throws(\InvalidArgumentException::class, 'The access token entity data is invalid.');

it('should create access token entity from array with DateTimeImmutable successfully', function () {
    $data = [
        'token' => 'Thisistoken',
        'expires_at' => Carbon::now()->addHours(1)->toDateTimeImmutable(),
    ];

    $accessToken = AccessTokenEntity::fromArray([
        'token' => $data['token'],
        'expires_at' => $data['expires_at'],
    ]);

    expect($accessToken)->toBeInstanceOf(AccessTokenEntityInterface::class);
    expect($accessToken->getToken())->toBe($data['token']);
    expect($accessToken->expiresAt()->format(Carbon::ATOM))->toBe($data['expires_at']->format(Carbon::ATOM));
});
