<?php

namespace Farzai\ThaiPost\Requests;

use Psr\Http\Message\RequestInterface as MessageRequestInterface;

interface RequestInterface
{
    /**
     * @return MessageRequestInterface
     */
    public function getRequest(): MessageRequestInterface;
}