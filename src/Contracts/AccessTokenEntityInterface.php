<?php

namespace Farzai\ThaiPost\Contracts;

use DateTimeImmutable;

interface AccessTokenEntityInterface
{
    /**
     * Get expires time
     */
    public function expiresAt(): DateTimeImmutable;

    /**
     * Get token
     */
    public function getToken(): string;
}
