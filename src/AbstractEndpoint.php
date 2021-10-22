<?php

namespace Farzai\ThaiPost;

use Farzai\ThaiPost\Contracts\TokenStore;
use Farzai\ThaiPost\Contracts\Endpoint as EndpointContract;
use Farzai\ThaiPost\Entity\TokenEntity;
use Farzai\ThaiPost\Exception\InvalidApiTokenException;
use Psr\Http\Client\ClientInterface;

abstract class AbstractEndpoint implements EndpointContract
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * @var TokenStore
     */
    protected $tokenStore;

    /**
     * @param Client $client
     * @return ClientInterface
     */
    abstract protected function getHttpClient(Client $client): ClientInterface;

    /**
     * @param Client $client
     * @return TokenEntity|null
     */
    abstract protected function fetchToken(Client $client);

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->httpClient = $this->getHttpClient($client);

        $this->setTokenStore($this->getDefaultTokenStore());
    }

    /**
     * @param TokenStore $tokenStore
     * @return $this
     */
    public function setTokenStore(TokenStore $tokenStore)
    {
        $this->tokenStore = $tokenStore;

        return $this;
    }

    /**
     * @return Transporter
     */
    public function getTransporter(): Transporter
    {
        $transporter = new Transporter($this->httpClient);

        if ($this->isTokenValid()) {
            $token = $this->tokenStore->get()->token;

            $transporter->setHeader('Authorization', "Token {$token}");
        }

        return $transporter;
    }


    /**
     * @param Request $request
     * @param callable $handler
     * @return mixed
     * @throws InvalidApiTokenException
     */
    protected function handleWithAuthorization(Request $request, callable $handler)
    {
        // If don't have token
        // Setup auth token before request
        if (! $this->isTokenValid()) {
            $this->fetchAndUpdateToken();
        }

        $response = $handler($request);

        // Fetch new token if invalid token
        if ($response->getResponse()->getStatusCode() === 403) {
            $this->fetchAndUpdateToken();
        }

        return $handler($request);
    }

    /**
     * @return TokenStore
     */
    protected function getDefaultTokenStore()
    {
        return new MemoryTokenStore;
    }

    /**
     * Check token
     *
     * @return bool
     */
    protected function isTokenValid()
    {
        if ($this->tokenStore->has()) {
            $token = $this->tokenStore->get();

            return $token && !$token->isExpired();
        }

        return false;
    }

    /**
     * @throws InvalidApiTokenException
     */
    private function fetchAndUpdateToken()
    {
        $token = $this->fetchToken($this->client);

        if (! $token) {
            throw new InvalidApiTokenException();
        }

        $this->tokenStore->save($token);
    }
}