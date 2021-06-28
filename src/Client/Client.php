<?php namespace Farzai\ThaiPost\Client;

use Farzai\ThaiPost\Auth\Credential;
use Farzai\ThaiPost\Requests\Request;
use Farzai\ThaiPost\Responses\Response;

class Client implements Factory, HttpClient
{
    /**
     * @var \Closure|string|HttpClient|null
     */
    protected $externalClient;

    /**
     * @var Credential
     */
    private $credentail;

    /**
     * Client constructor.
     * @param Credential $credential
     */
    public function __construct(Credential $credential)
    {
        $this->credentail = $credential;
    }

    /**
     * @param \Closure|string|HttpClient $external
     * @return $this
     */
    public function shouldUse($external)
    {
        $this->externalClient = $external;

        return $this;
    }

    /**
     * Forget external client
     *
     * @return $this
     */
    public function forgetExternal()
    {
        $this->externalClient = null;

        return $this;
    }

    /**
     * @return Client
     */
    public function createClient(): HttpClient
    {
        $client = $this->externalClient ?: function ($credential) {
            return new GuzzleClient($credential);
        };

        if (is_callable($client)) {
            $client = $client($this->credentail);
        }

        if (is_string($client)) {
            $client = new $client($this->credentail);
        }

        return $client;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function execute(Request $request): Response
    {
        return $this->createClient()->execute($request);
    }
}