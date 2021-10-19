<?php

namespace Farzai\ThaiPost\Auth;

class SessionStore implements TokenStoreInterface
{

    public function __construct()
    {
        @session_start();
    }

    /**
     * @param string $token
     */
    public function store(string $token)
    {
        $_SESSION['THAIPOST_TOKEN'] = $token;
    }

    /**
     * @return string|null
     */
    public function get()
    {
        return $_SESSION['THAIPOST_TOKEN'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->get() ?: '';
    }
}