<?php namespace Farzai\ThaiPost\Client;

use Farzai\ThaiPost\Requests\Request;
use Farzai\ThaiPost\Responses\Response;

interface HttpClient
{
    /**
     * @param Request $request
     * @return Response
     */
    public function execute(Request $request);
}