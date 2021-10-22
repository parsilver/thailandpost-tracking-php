<?php

namespace Farzai\ThaiPost;

use Farzai\ThaiPost\Contracts\TokenStore;
use Farzai\ThaiPost\Entity\TokenEntity;

class MemoryTokenStore implements TokenStore
{

    /**
     * @var TokenEntity|null
     */
    private $token;

    /**
     * @param TokenEntity $token
     * @return mixed
     */
    public function save(TokenEntity $token)
    {
        $this->token = $token;
    }

    /**
     * @return TokenEntity|null
     */
    public function get()
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        if ($this->has()) {
            return $this->get()->token;
        }

        return '';
    }

    /**
     * @return bool
     */
    public function has()
    {
        return ! is_null($this->token);
    }
}