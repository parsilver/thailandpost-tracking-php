<?php namespace Farzai\ThaiPost\Requests;

use Farzai\ThaiPost\Responses\Response;
use Psr\Http\Message\ResponseInterface;

interface Request
{
    /**
     * @return string|bool
     */
    public function method($name = null);

    /**
     * @return string
     */
    public function uri();

    /**
     * @return array
     */
    public function payload();

    /**
     * @return array
     */
    public function headers();
}