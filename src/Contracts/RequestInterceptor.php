<?php

namespace Farzai\ThaiPost\Contracts;

use Psr\Http\Message\RequestInterface as PsrRequestInterface;

interface RequestInterceptor
{
    /**
     * Apply the request.
     */
    public function apply(PsrRequestInterface $request): PsrRequestInterface;
}
