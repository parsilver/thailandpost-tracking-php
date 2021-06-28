<?php namespace Farzai\ThaiPost\Auth;

interface Credential
{
    /**
     * @return string
     */
    public function getResponseType();

    /**
     * @return mixed
     */
    public function __toString();
}