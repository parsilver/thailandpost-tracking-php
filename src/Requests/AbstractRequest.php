<?php namespace Farzai\ThaiPost\Requests;

use Farzai\ThaiPost\Responses\JsonResponse;
use Farzai\ThaiPost\Responses\Response;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractRequest implements Request, WithTransformResponse
{
    /**
     * @var string
     */
    private $endpoint;

    /**
     * @var string
     */
    private $method;

    /**
     * AbstractRequest constructor.
     * @param string $endpoint
     * @param string $method
     */
    public function __construct(string $endpoint, string $method = "GET")
    {
        $this->endpoint = $endpoint;
        $this->method = $method;
    }

    /**
     * @return string|boolean
     */
    public function method($name = null)
    {
        if ($name) {
            return strtolower($name) === strtolower($this->method);
        }

        return $this->method;
    }

    /**
     * @return string
     */
    public function uri()
    {
        return $this->endpoint;
    }

    /**
     * @return array
     */
    public function headers()
    {
        return [];
    }

    /**
     * @return array
     */
    public function payload()
    {
        return [];
    }

    /**
     * @param Request $request
     * @param ResponseInterface $response
     * @return Response
     */
    public function toResponse(Request $request, ResponseInterface $response)
    {
        return new JsonResponse($request, $response);
    }
}