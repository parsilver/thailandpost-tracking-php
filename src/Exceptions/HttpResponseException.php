<?php

namespace Farzai\ThaiPost\Exceptions;

use Exception;

class HttpResponseException extends Exception
{
    /**
     * @var int|mixed
     */
    public $statusCode;

    /**
     * @param  int  $httpStatusCode
     */
    public function __construct(string $message, $httpStatusCode = 400)
    {
        parent::__construct($message, 100);

        $this->statusCode = $httpStatusCode;
    }
}
