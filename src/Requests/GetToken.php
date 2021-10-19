<?php

namespace Farzai\ThaiPost\Requests;

class GetToken extends Request
{
    /**
     * @param string $apiKey
     */
    public function __construct(string $apiKey)
    {
        $this->method = 'POST';
        $this->path = '/post/api/v1/authenticate/token';
        $this->headers['Authorization'] = "Token {$apiKey}";
        $this->headers['Content-Type'] = 'application/json';
    }
}