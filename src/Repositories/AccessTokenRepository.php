<?php

namespace Farzai\ThaiPost\Repositories;

use Farzai\ThaiPost\AccessTokenEntity;
use Farzai\ThaiPost\Contracts\AccessTokenEntityInterface;
use Farzai\ThaiPost\Contracts\AccessTokenRepositoryInterface;
use Farzai\ThaiPost\Contracts\StorageRepositoryInterface;
use Farzai\ThaiPost\Exceptions\AccessTokenException;

class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    /**
     * AccessTokenRepository constructor.
     */
    public function __construct(
        protected string $name,
        protected StorageRepositoryInterface $storage,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function getToken(): AccessTokenEntityInterface
    {
        if ($this->storage->has($this->name)) {
            $data = @json_decode($this->storage->get($this->name), true);

            if (json_last_error() === JSON_ERROR_NONE) {
                $token = AccessTokenEntity::fromArray($data);

                if ($token->isExpired()) {
                    throw new AccessTokenException('Access token has expired.');
                }

                return $token;
            }
        }

        throw new AccessTokenException('Invalid access token.');
    }

    /**
     * {@inheritDoc}
     */
    public function saveToken(AccessTokenEntityInterface $accessToken): void
    {
        $this->storage->create($this->name, json_encode($accessToken));
    }

    /**
     * {@inheritDoc}
     */
    public function forget(): void
    {
        $this->storage->delete($this->name);
    }
}
