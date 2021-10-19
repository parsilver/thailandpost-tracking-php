<?php

namespace Farzai\ThaiPost\Response;

use Psr\Http\Message\ResponseInterface as MessageResponseInterface;
use Farzai\ThaiPost\Support\Arr;

class Response implements ResponseInterface
{
    /**
     * @var MessageResponseInterface
     */
    protected $response;

    /**
     * @var array|null
     */
    protected $json;

    /**
     * AbstractResponse constructor.
     * @param MessageResponseInterface $response
     */
    public function __construct(MessageResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * @return bool
     */
    public function isOk()
    {
        return $this->response->getStatusCode() >= 200
            && $this->response->getStatusCode() < 300;
    }

    /**
     * @param string|null $key
     * @param null $default
     * @return mixed
     */
    public function json($key = null, $default = null)
    {
        if (is_null($this->json)) {
            $this->json = @json_decode($this->getResponse()->getBody(), true) ?: false;
        }

        return is_null($key) ? $this->json : Arr::get($this->json, $key, $default);
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

    /**
     * @return MessageResponseInterface
     */
    public function getResponse(): MessageResponseInterface
    {
        return $this->response;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->response->getBody();
    }
}