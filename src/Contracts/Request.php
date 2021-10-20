<?php

namespace Farzai\ThaiPost\Contracts;

use Psr\Http\Message\RequestInterface as MessageRequestInterface;

interface Request
{
    /**
     * @return MessageRequestInterface
     */
    public function getRequest(): MessageRequestInterface;
}