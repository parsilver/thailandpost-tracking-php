<?php namespace Farzai\ThaiPost\Client;

use Farzai\ThaiPost\Auth\Credential;
use Farzai\ThaiPost\Requests\Request;
use Farzai\ThaiPost\Requests\WithTransformResponse;
use Farzai\ThaiPost\Responses\JsonResponse;
use Farzai\ThaiPost\Responses\Response;
use GuzzleHttp\Client as GuzzleHttp;
use InvalidArgumentException;

class GuzzleClient implements HttpClient
{
    /**
     * @var array
     */
    private $options = [
        'headers' => [
            'Content-Type' => 'application/json',
            'accept' => 'application/json',
        ]
    ];

    /**
     * @var Credential
     */
    private $credential;

    /**
     * GuzzleClient constructor.
     * @param Credential $credential
     * @param array $options
     */
    public function __construct(Credential $credential, array $options = [])
    {
        $this->credential = $credential;
        $this->options = array_merge($this->options, $options);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function execute(Request $request): Response
    {
        $guzzle = new GuzzleHttp(array_merge($this->options, [
            'headers' => $this->buildHeaders($request),
        ]));

        if ($request->method('post')) {
            $options['json'] = $request->payload();
        }

        $response = $guzzle->request(
            $request->method(),
            $request->uri(),
            $options
        );

        if ($request instanceof WithTransformResponse) {
            return $request->toResponse($request, $response);
        }

        return new JsonResponse($request, $response);
    }

    /**
     * @return string
     */
    private function getToken()
    {
        switch ($type = $this->credential->getResponseType()) {
            case 'secret':
            case 'token':
                return "Token {$this->credential}";
        }

        throw new InvalidArgumentException("Unsupported credential type: {$type}");
    }

    /**
     * @param Request $request
     * @return array
     */
    private function buildHeaders(Request $request)
    {
        $headers = array_merge($this->options['headers'], $request->headers());

        $headers['Authorization'] = $this->getToken();

        return $headers;
    }
}