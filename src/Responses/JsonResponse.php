<?php namespace Farzai\ThaiPost\Responses;

use Farzai\ThaiPost\Requests\Request;
use Farzai\ThaiPost\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Class JsonResponse
 * @package Farzai\ThaiPost\Responses
 */
class JsonResponse implements Response
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var ResponseInterface
     */
    protected $baseResponse;

    /**
     * @var array|null
     */
    private $jsonBody;

    /**
     * AbstractResponse constructor.
     * @param Request $request
     * @param ResponseInterface $response
     */
    public function __construct(Request $request, ResponseInterface $response)
    {
        $this->request = $request;
        $this->baseResponse = $response;
    }

    /**
     * @return bool
     */
    public function isStatusSuccess()
    {
        return $this->json('status') === true;
    }

    /**
     * @return bool
     */
    public function ok()
    {
        return $this->getStatusCode() >= 200
            && $this->getStatusCode() < 300;
    }

    /**
     * @return bool
     */
    public function isClientError()
    {
        return $this->getStatusCode() >= 400
            && $this->getStatusCode() < 500;
    }

    /**
     * @return bool
     */
    public function isServerError()
    {
        return $this->getStatusCode() >= 500;
    }

    /**
     * @param null $key
     * @return array|mixed|null
     */
    public function json($key = null, $default = null)
    {
        if (! $this->jsonBody) {
            $this->jsonBody = @json_decode($this->getBody(), true);
        }

        return is_null($key)
            ? $this->jsonBody
            : Arr::get($this->jsonBody, $key, $default);
    }

    /**
     * @return string
     */
    public function getProtocolVersion()
    {
        return $this->baseResponse->getProtocolVersion();
    }

    /**
     * @param string $version
     * @return JsonResponse|ResponseInterface
     */
    public function withProtocolVersion($version)
    {
        return $this->baseResponse->withProtocolVersion($version);
    }

    /**
     * @return \string[][]
     */
    public function getHeaders()
    {
        return $this->baseResponse->getHeaders();
    }

    /**
     * @param string $name
     * @return bool|void
     */
    public function hasHeader($name)
    {
        return $this->baseResponse->hasHeader($name);
    }

    /**
     * @param string $name
     * @return string[]
     */
    public function getHeader($name)
    {
        return $this->baseResponse->getHeader($name);
    }

    public function getHeaderLine($name)
    {
        return $this->baseResponse->getHeaderLine($name);
    }

    /**
     * @param string $name
     * @param string|string[] $value
     * @return JsonResponse|ResponseInterface
     */
    public function withHeader($name, $value)
    {
        return $this->baseResponse->withHeader($name, $value);
    }

    /**
     * @param string $name
     * @param string|string[] $value
     * @return JsonResponse|ResponseInterface
     */
    public function withAddedHeader($name, $value)
    {
        return $this->baseResponse->withAddedHeader($name, $value);
    }

    /**
     * @param string $name
     * @return JsonResponse|ResponseInterface
     */
    public function withoutHeader($name)
    {
        return $this->baseResponse->withoutHeader($name);
    }

    /**
     * @return StreamInterface
     */
    public function getBody()
    {
        return $this->baseResponse->getBody();
    }

    /**
     * @param StreamInterface $body
     * @return void
     */
    public function withBody(StreamInterface $body)
    {
        $this->baseResponse->withBody($body);
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->baseResponse->getStatusCode();
    }

    /**
     * @param int $code
     * @param string $reasonPhrase
     * @return JsonResponse|ResponseInterface
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        return $this->baseResponse->withStatus($code, $reasonPhrase);
    }

    /**
     * @return string
     */
    public function getReasonPhrase()
    {
        return $this->baseResponse->getReasonPhrase();
    }

    /**
     * @return ResponseInterface
     */
    public function getBaseResponse()
    {
        return $this->baseResponse;
    }

    /**
     * @param $name
     * @return array|mixed|null
     */
    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->{$name};
        }

        return $this->json($name);
    }
}