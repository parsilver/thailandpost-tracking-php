<?php

namespace Farzai\ThaiPost\Exceptions;

use Exception;

class UnauthorizedException extends Exception
{
    public function __construct(
        $message = "Unauthorized",
        $code = 401,
        Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
