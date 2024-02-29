<?php

namespace Farzai\ThaiPost;

use Farzai\Support\Arr;
use Farzai\ThaiPost\Contracts\AccessTokenRepositoryInterface;
use Farzai\Transport\Transport;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface as PsrRequestInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Log\LoggerInterface;

class Client
{
    /**
     * Create a new Client instance.
     *
     * @param  array<string, mixed>  $config
     */
    public function __construct(
        private array $config,
        private Transport $transport,
        private LoggerInterface $logger,
        private AccessTokenRepositoryInterface $accessTokenRepository
    ) {
    }

    /**
     * Get client config.
     */
    public function getConfig(?string $key = null): mixed
    {
        if (is_null($key)) {
            return $this->config;
        }

        return Arr::get($this->config, $key);
    }

    /**
     * Get transport.
     */
    public function getTransport(): Transport
    {
        return $this->transport;
    }

    /**
     * Get logger.
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * Get http client.
     */
    public function getHttpClient(): ClientInterface
    {
        return $this->transport->getPsrClient();
    }

    /**
     * Get access token repository.
     */
    public function getAccessTokenRepository(): AccessTokenRepositoryInterface
    {
        return $this->accessTokenRepository;
    }

    /**
     * Send the request.
     */
    public function sendRequest(PsrRequestInterface $request): PsrResponseInterface
    {
        return $this->transport->sendRequest($request);
    }
}
