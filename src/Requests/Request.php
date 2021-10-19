<?php

namespace Farzai\ThaiPost\Requests;

use Psr\Http\Message\RequestInterface as MessageRequestInterface;
use GuzzleHttp\Psr7\Request as GuzzleHttpRequest;

class Request implements RequestInterface
{
    /**
     * @var string
     */
    protected $method = 'GET';

    /**
     * @var string
     */
    protected $path = '';

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var array
     */
    protected $queryParams = [];

    /**
     * @var mixed
     */
    protected $body;

    /**
     * Returns a PSR-7 request
     */
    public function getRequest(): MessageRequestInterface
    {
        $path = $this->path;
        if (! empty($this->queryParams)) {
            $path .= '?' . http_build_query($this->queryParams);
        }

        return new GuzzleHttpRequest(
            $this->method,
            $path,
            $this->headers,
            $this->body,
        );
    }

    /**
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function setHeader(string $name, string $value)
    {
        $this->headers[$name] = $value;

        return $this;
    }
}