<?php

namespace Farzai\ThaiPost\Contracts;

use DateTimeImmutable;
use JsonSerializable;

interface AccessTokenEntityInterface extends JsonSerializable
{
    /**
     * Get expires time
     */
    public function expiresAt(): DateTimeImmutable;

    /**
     * Get token
     */
    public function getToken(): string;

    /**
     * Check if the token is expired
     */
    public function isExpired(): bool;

    /**
     * Convert the entity to an array
     */
    public function toArray(): array;
}
