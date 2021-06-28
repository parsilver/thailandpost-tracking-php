<?php namespace Farzai\ThaiPost\Requests;

class GetTokenRequest extends AbstractRequest
{
    /**
     * @var string
     */
    protected $secretToken;

    /**
     * GetTokenRequest constructor.
     * @param string $secretToken
     */
    public function __construct(string $secretToken)
    {
        parent::__construct("https://trackapi.thailandpost.co.th/post/api/v1/authenticate/token", "POST");

        $this->secretToken = $secretToken;
    }

    /**
     * @return string[]
     */
    public function headers()
    {
        return [
            'Authorization' => "Token {$this->secretToken}"
        ];
    }
}