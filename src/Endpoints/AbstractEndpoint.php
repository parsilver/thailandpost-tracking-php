<?php

namespace Farzai\ThaiPost\Endpoints;

use Farzai\ThaiPost\Client;
use Farzai\ThaiPost\PendingRequest;
use Farzai\ThaiPost\Authorizer;
use Farzai\ThaiPost\Contracts\AccessTokenRepositoryInterface;

abstract class AbstractEndpoint
{
    /**
     * Get the endpoint URI.
     */
    abstract protected function getUri(): string;

    /**
     * Get the access token repository.
     */
    abstract protected function getAccessTokenRepository(): AccessTokenRepositoryInterface;

    /**
     * @var \Farzai\ThaiPost\Client
     */
    protected $client;

    /**
     * Create a new endpoint instance.
     */
    public function __construct(Client $client)
    {
        $this->client = clone $client;

        $transport = $this->client->getTransport();
        $transport->setUri($this->getUri());
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
        return new Authorizer($this->client, $this->getAccessTokenRepository());
    }
}
