<?php

namespace Farzai\ThaiPost\Endpoints;

use Farzai\ThaiPost\Authorizer;
use Farzai\ThaiPost\Client;
use Farzai\ThaiPost\Contracts\AccessTokenRepositoryInterface;
use Farzai\ThaiPost\PendingRequest;

abstract class AbstractEndpoint
{
    /**
     * Get the endpoint URI.
     */
    abstract protected function getUri(): string;

    /**
     * @var \Farzai\ThaiPost\Client
     */
    protected Client $client;

    /**
     * @var \Farzai\ThaiPost\Contracts\AccessTokenRepositoryInterface
     */
    protected AccessTokenRepositoryInterface $accessTokenRepository;

    /**
     * Create a new endpoint instance.
     */
    public function __construct(Client $client, AccessTokenRepositoryInterface $accessTokenRepository)
    {
        $this->client = clone $client;

        $transport = $this->client->getTransport();
        $transport->setUri($this->getUri());

        $this->accessTokenRepository = $accessTokenRepository;
    }

    /**
     * Get client.
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * Make a new request.
     */
    protected function makeRequest(
        string $method,
        string $path,
        array $options = []
    ): PendingRequest {
        return new PendingRequest($this->client, $method, $path, $options);
    }

    protected function getAuthorzier(): Authorizer
    {
        return new Authorizer($this->client, $this->accessTokenRepository);
    }
}
