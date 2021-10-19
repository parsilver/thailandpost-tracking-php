<?php namespace Farzai\ThaiPost\Response;

use Psr\Http\Message\ResponseInterface as MessageResponseInterface;

interface ResponseInterface
{
    /**
     * @return bool
     */
    public function isOk();

    /**
     * Get json body
     *
     * @param string|null $key
     * @param mixed|null $default
     * @return mixed
     */
    public function json($key = null, $default = null);

    /**
     * @return MessageResponseInterface
     */
    public function getResponse(): MessageResponseInterface;
}