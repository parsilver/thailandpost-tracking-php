<?php

namespace Farzai\ThaiPost\Contracts;

use Farzai\ThaiPost\Repository\TokenRepository;

interface TokenStore extends TokenRepository
{
    /**
     * @return string
     */
    public function __toString();
}