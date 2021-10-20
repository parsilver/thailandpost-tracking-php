<?php

namespace Farzai\ThaiPost\RestApi\Auth;

use Farzai\ThaiPost\Contracts\TokenStore;
use Farzai\ThaiPost\Entity\TokenEntity;

class SessionToken implements TokenStore
{

    public function __construct()
    {
        @session_start();
    }

    /**
     * @param TokenEntity $token
     * @return mixed
     */
    public function save(TokenEntity $token)
    {
        $_SESSION['THAIPOST_TOKEN'] = $token->asJson();
    }

    /**
     * @return TokenEntity|null
     */
    public function get()
    {
        $json = @json_decode($_SESSION['THAIPOST_TOKEN'] ?? '', true);

        if ($json) {
            return TokenEntity::fromArray($json);
        }
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
        return ! is_null($_SESSION['THAIPOST_TOKEN'] ?? null);
    }
}