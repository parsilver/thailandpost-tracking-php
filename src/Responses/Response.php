<?php namespace Farzai\ThaiPost\Responses;

use Psr\Http\Message\ResponseInterface;

interface Response extends ResponseInterface
{
    /**
     * @return boolean
     */
    public function ok();

    /**
     * @return boolean
     */
    public function isClientError();

    /**
     * @return boolean
     */
    public function isServerError();

    /**
     * Get json body
     *
     * @param string|null $key
     * @param mixed|null $default
     * @return array|null
     */
    public function json($key = null, $default = null);
}