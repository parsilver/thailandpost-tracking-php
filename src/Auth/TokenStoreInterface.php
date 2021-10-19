<?php

namespace Farzai\ThaiPost\Auth;

interface TokenStoreInterface
{
    /**
     * Save token
     *
     * @param string $token
     */
    public function store(string $token);

    /**
     * Get token
     *
     * @return string
     */
    public function get();

    /**
     * @return string
     */
    public function __toString();
}