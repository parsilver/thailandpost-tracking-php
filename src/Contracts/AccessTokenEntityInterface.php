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
     * Convert the entity to an array
     *
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
