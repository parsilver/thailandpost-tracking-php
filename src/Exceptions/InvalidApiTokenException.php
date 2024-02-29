<?php

namespace Farzai\ThaiPost\Exception;

class InvalidApiTokenException extends HttpResponseException
{
    /**
     * @param  string  $message
     */
    public function __construct($message = 'Invalid api key, Please check api token from your dashboard at https://track.thailandpost.co.th/dashboard.')
    {
        parent::__construct($message);
    }
}
