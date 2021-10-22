<?php

namespace Farzai\ThaiPost\Exception;

use Exception;
use Throwable;

class HttpResponseException extends Exception
{
    /**
     * @var int|mixed
     */
    public $statusCode;

    /**
     * @param string $message
     * @param int $httpStatusCode
     */
    public function __construct(string $message, $httpStatusCode = 400)
    {
        parent::__construct($message, 100);

        $this->statusCode = $httpStatusCode;
    }
}