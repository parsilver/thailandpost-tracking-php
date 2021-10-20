<?php

namespace Farzai\ThaiPost;

use Farzai\ThaiPost\Contracts\TokenStore;
use Farzai\ThaiPost\Contracts\Endpoint as EndpointContract;
use Farzai\ThaiPost\Entity\TokenEntity;
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
     * @return TokenEntity
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    abstract protected function fetchToken(Client $client): TokenEntity;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->httpClient = $this->getHttpClient($client);
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

        if ($this->tokenStore->has()) {
            $token = $this->tokenStore->get()->token;

            $transporter->setHeader('Authorization', "{$this->getTokenType()} {$token}");
        }

        return $transporter;
    }


    /**
     * @param Request $request
     * @param callable $handler
     * @return mixed
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    protected function handleWithAuthorization(Request $request, callable $handler)
    {
        $response = $handler($request);

        if ($response->getResponse()->getStatusCode() === 403) {
            $this->tokenStore->save($this->fetchToken($this->client));
        }

        return $handler($request);
    }

    /**
     * @return string
     */
    protected function getTokenType()
    {
        return 'Token';
    }
}