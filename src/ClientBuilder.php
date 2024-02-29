<?php

namespace Farzai\ThaiPost;

use Farzai\ThaiPost\Contracts\AccessTokenRepositoryInterface;
use Farzai\ThaiPost\Contracts\StorageRepositoryInterface;
use Farzai\ThaiPost\Repositories\AccessTokenRepository;
use Farzai\ThaiPost\Repositories\FilesystemCacheStorageRepository;
use Farzai\Transport\TransportBuilder;
use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class ClientBuilder
{
    /**
     * Config for the Client.
     *
     * @var array<string, mixed>
     */
    private array $config = [];

    private ?LoggerInterface $logger = null;

    private ?ClientInterface $httpClient = null;

    private ?AccessTokenRepositoryInterface $accessTokenRepository = null;

    private ?StorageRepositoryInterface $storageRepository = null;

    /**
     * Create a new ClientBuilder instance.
     */
    public static function create(): self
    {
        return new static();
    }

    /**
     * Set config.
     *
     * @param  array<string, mixed>  $config
     */
    public function setConfig(array $config): self
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Set credential.
     */
    public function setCredential(string $token): self
    {
        return $this->setConfig(array_merge($this->config, [
            'token' => $token,
        ]));
    }

    /**
     * Set access token repository.
     */
    public function setAccessTokenRepository(AccessTokenRepositoryInterface $accessTokenRepository): self
    {
        $this->accessTokenRepository = $accessTokenRepository;

        return $this;
    }

    /**
     * Set storage repository.
     */
    public function setStorageRepository(StorageRepositoryInterface $storageRepository): self
    {
        $this->storageRepository = $storageRepository;

        return $this;
    }

    /**
     * Set logger.
     */
    public function setLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * Set http client.
     */
    public function setHttpClient(ClientInterface $httpClient): self
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    /**
     * Build the Client.
     */
    public function build(): Client
    {
        $config = $this->config;

        // Check token must be set.
        if (empty($config['token'] ?? null)) {
            throw new \InvalidArgumentException('Please specify token in config.');
        }

        $builder = TransportBuilder::make();

        if ($this->httpClient) {
            $builder->setClient($this->httpClient);
        }

        $builder->setLogger(
            $logger = $this->logger ?? new NullLogger(),
        );

        $transport = $builder->build();

        return new Client(
            config: $config,
            transport: $transport,
            logger: $logger,
            accessTokenRepository: $this->accessTokenRepository ?? new AccessTokenRepository(
                storage: $this->storageRepository ?? new FilesystemCacheStorageRepository(),
            ),
        );
    }
}
