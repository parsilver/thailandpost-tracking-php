<?php

namespace Farzai\ThaiPost;

use DateTimeImmutable;
use Farzai\Support\Carbon;
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
     * Create a new access token entity from an array.
     *
     * @return static
     *
     * @throws \InvalidArgumentException
     */
    public static function fromArray(array $data): self
    {
        // Validate the access token entity data.
        if (! isset($data['token'], $data['expires_at'])) {
            throw new \InvalidArgumentException(
                'The access token entity data is invalid.'
            );
        }

        if (is_string($data['expires_at'])) {
            $expiresAt = Carbon::parse(
                $data['expires_at']
            )->toDateTimeImmutable();
        } elseif ($data['expires_at'] instanceof DateTimeImmutable) {
            $expiresAt = $data['expires_at'];
        } else {
            throw new \InvalidArgumentException(
                'The access token entity data is invalid.'
            );
        }

        return new self($data['token'], $expiresAt);
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

    /**
     * {@inheritDoc}
     */
    public function isExpired(): bool
    {
        return $this->expiresAt->getTimestamp() < time();
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'token' => $this->token,
            'expires_at' => $this->expiresAt->format(Carbon::ATOM),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
