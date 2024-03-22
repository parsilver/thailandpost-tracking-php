<?php

namespace Farzai\ThaiPost\Contracts;

interface AccessTokenRepositoryInterface
{
    /**
     * Get access token.
     * If the access token is not found or expired, it should throw an AccessTokenException.
     *
     * @throws \Farzai\ThaiPost\Exceptions\AccessTokenException
     */
    public function getToken(): AccessTokenEntityInterface;

    /**
     * Save access token.
     */
    public function saveToken(AccessTokenEntityInterface $accessToken): void;

    /**
     * Clear access token.
     */
    public function forget(): void;
}
