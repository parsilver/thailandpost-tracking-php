<?php

namespace Farzai\ThaiPost\Contracts;

use Psr\Http\Message\RequestInterface as MessageRequestInterface;

interface Request
{
    /**
     * Get psr7 request.
     * 
     * @return MessageRequestInterface
     */
    public function getRequest(): MessageRequestInterface;
}
