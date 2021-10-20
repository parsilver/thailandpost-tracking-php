<?php

namespace Farzai\ThaiPost\RestApi\Requests;

use Farzai\ThaiPost\Request;

class GetToken extends Request
{
    /**
     * @param string $apiKey
     */
    public function __construct(string $apiKey)
    {
        $this->method = 'POST';
        $this->path = '/post/api/v1/authenticate/token';

        $this->setHeader('Authorization', "Token {$apiKey}");
        $this->setHeader('Content-Type', "application/json");
    }
}