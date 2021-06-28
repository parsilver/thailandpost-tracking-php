<?php namespace Farzai\ThaiPost\Auth;

class AuthTokenCredential implements Credential
{
    /**
     * @var string
     */
    private $token;

    /**
     * ApiTokenCredential constructor.
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getResponseType()
    {
        return 'token';
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->token;
    }
}