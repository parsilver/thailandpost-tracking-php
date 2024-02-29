<?php

namespace Farzai\ThaiPost\Contracts;

interface AccessTokenRepositoryInterface
{
    /**
     * Get access token.
     *
     * @throws \Farzai\ThaiPost\Exceptions\AccessTokenException
     */
    public function getToken(): AccessTokenEntityInterface;

    /**
     * Save access token.
     *
     *
     * @throws \Farzai\ThaiPost\Exceptions\AccessTokenException
     */
    public function saveToken(AccessTokenEntityInterface $accessToken): void;

    /**
     * Clear access token.
     */
    public function forget(): void;
}
