<?php

namespace Farzai\ThaiPost\Endpoints;

use Farzai\ThaiPost\Authorizer;
use Farzai\ThaiPost\Client;
use Farzai\ThaiPost\Contracts\AccessTokenRepositoryInterface;
use Farzai\ThaiPost\PendingRequest;
use Farzai\Transport\Transport;

abstract class AbstractEndpoint
{
    /**
     * Get the endpoint URI.
     */
    abstract protected function getUri(): string;

    protected Client $client;

    protected AccessTokenRepositoryInterface $accessTokenRepository;

    /**
     * Create a new endpoint instance.
     */
    public function __construct(Client $client, AccessTokenRepositoryInterface $accessTokenRepository)
    {
        $this->client = clone $client;

        // Transport 2.x uses an immutable config, so rebuild the transport with
        // the endpoint's base URI instead of mutating it in place.
        $transport = $this->client->getTransport();
        $this->client->setTransport(
            new Transport($transport->getConfig()->withBaseUri($this->getUri()))
        );

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
