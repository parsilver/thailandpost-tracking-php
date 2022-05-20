<?php

namespace Farzai\ThaiPost\Contracts;

use Farzai\ThaiPost\Repository\TokenRepository;

interface TokenStore extends TokenRepository
{
    /**
     * Transform token to string.
     * 
     * @return string
     */
    public function __toString();
}
