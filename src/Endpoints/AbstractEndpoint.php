<?php

namespace Farzai\ThaiPost\Endpoints;

use Farzai\ThaiPost\Client;
use Farzai\ThaiPost\PendingRequest;

abstract class AbstractEndpoint
{
    /**
     * @var \Farzai\ThaiPost\Client
     */
    protected $client;

    abstract public function getUri(): string;

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
}
