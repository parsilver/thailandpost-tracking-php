<?php

namespace Farzai\ThaiPost;

use DateTimeImmutable;
use Farzai\Support\Carbon;
use Farzai\ThaiPost\Contracts\AccessTokenEntityInterface;

class AccessTokenEntity implements AccessTokenEntityInterface
{
    /**
     * AccessTokenEntity constructor.
     */
    public function __construct(
        public readonly string $token,
        public readonly DateTimeImmutable $expiresAt,
    ) {
    }

    /**
     * Create a new access token entity from an array.
     *
     * @param array{token: string, expires_at: string|DateTimeImmutable} $data
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
            // Try to parse the expires at date time.
            try {
                $expiresAt = Carbon::parse(
                    $data['expires_at']
                )->toDateTimeImmutable();
            } catch (\Exception $e) {
                throw new \InvalidArgumentException(
                    'The access token entity data is invalid.'
                );
            }
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

    public function __toString()
    {
        return $this->token;
    }
}
