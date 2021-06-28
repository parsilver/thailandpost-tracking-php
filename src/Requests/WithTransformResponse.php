<?php namespace Farzai\ThaiPost\Requests;

use Farzai\ThaiPost\Responses\Response;
use Psr\Http\Message\ResponseInterface;

interface WithTransformResponse
{

    /**
     * @param Request $request
     * @param ResponseInterface $response
     * @return Response
     */
    public function toResponse(Request $request, ResponseInterface $response);
}