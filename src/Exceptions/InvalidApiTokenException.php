<?php

namespace Farzai\ThaiPost\Exceptions;

class InvalidApiTokenException extends HttpResponseException
{
    /**
     * @param  string  $message
     */
    public function __construct(
        $message = 'Invalid api key, Please check api token from your dashboard at https://track.thailandpost.co.th/dashboard.',
        $code = 401
    ) {
        parent::__construct($message);
    }
}
