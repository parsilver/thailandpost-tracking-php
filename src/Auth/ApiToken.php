<?php namespace Farzai\ThaiPost\Auth;

class ApiToken implements Credential
{

    /**
     * @var string
     */
    private $secretToken;

    /**
     * SecretTokenCredential constructor.
     * @param string $secretToken
     */
    public function __construct(string $secretToken)
    {
        $this->secretToken = $secretToken;
    }

    /**
     * @return string
     */
    public function getResponseType()
    {
        return 'secret';
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->secretToken;
    }
}