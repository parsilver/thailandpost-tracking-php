<?php

namespace Farzai\ThaiPost\Repository;

use Farzai\ThaiPost\Entity\TokenEntity;

interface TokenRepository extends RepositoryInterface
{
    /**
     * @param TokenEntity $token
     * @return mixed
     */
    public function save(TokenEntity $token);

    /**
     * @return TokenEntity|null
     */
    public function get();

    /**
     * Check token has stored
     *
     * @return bool
     */
    public function has();
}