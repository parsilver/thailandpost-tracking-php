<?php

namespace Farzai\ThaiPost\Repositories;

use Farzai\Support\Carbon;
use Farzai\ThaiPost\AccessTokenEntity;
use Farzai\ThaiPost\Contracts\AccessTokenEntityInterface;
use Farzai\ThaiPost\Contracts\AccessTokenRepositoryInterface;
use Farzai\ThaiPost\Contracts\StorageRepositoryInterface;
use Farzai\ThaiPost\Exceptions\AccessTokenException;

class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    /**
     * @var \Farzai\ThaiPost\Contracts\StorageRepositoryInterface
     */
    protected $storage;

    /**
     * @var string
     */
    protected $name;

    /**
     * AccessTokenRepository constructor.
     */
    public function __construct(StorageRepositoryInterface $storage)
    {
        $this->name = 'access-token';

        $this->storage = $storage;
    }

    /**
     * {@inheritDoc}
     */
    public function getToken(): AccessTokenEntityInterface
    {
        if ($this->storage->has($this->name)) {
            $data = $this->storage->get($this->name);

            $data = @json_decode($data, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                return new AccessTokenEntity(
                    $data['token'],
                    Carbon::parse($data['expires_at'])->toDateTimeImmutable(),
                );
            }
        }

        throw new AccessTokenException('Access token not found.');
    }

    /**
     * {@inheritDoc}
     */
    public function saveToken(AccessTokenEntityInterface $accessToken): void
    {
        $this->storage->create($this->name, json_encode([
            'token' => $accessToken->getToken(),
            'expires_at' => $accessToken->expiresAt()->format(Carbon::ATOM),
        ]));
    }

    /**
     * {@inheritDoc}
     */
    public function forget(): void
    {
        $this->storage->delete($this->name);
    }
}
