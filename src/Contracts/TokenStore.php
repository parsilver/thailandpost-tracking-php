<?php

namespace Farzai\ThaiPost\Contracts;

use Farzai\ThaiPost\Entity\TokenEntity;

interface TokenStore
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