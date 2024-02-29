<?php

namespace Farzai\ThaiPost;

use DateTimeImmutable;
use Farzai\ThaiPost\Contracts\AccessTokenEntityInterface;

class AccessTokenEntity implements AccessTokenEntityInterface
{
    /**
     * @var string
     */
    protected $token;

    /**
     * @var \DateTimeImmutable
     */
    protected $expiresAt;

    /**
     * AccessTokenEntity constructor.
     */
    public function __construct(string $token, DateTimeImmutable $expiresAt)
    {
        $this->token = $token;
        $this->expiresAt = $expiresAt;
    }

    /**
     * {@inheritDoc}
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * {@inheritDoc}
     */
    public function expiresAt(): DateTimeImmutable
    {
        return $this->expiresAt;
    }
}
