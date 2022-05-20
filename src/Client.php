<?php

namespace Farzai\ThaiPost;

use Farzai\ThaiPost\Support\Arr;
use GuzzleHttp\Client as GuzzleHttp;
use Psr\Http\Client\ClientInterface;

class Client
{
    /**
     * Version of the Library.
     */
    const VERSION = '1.0.0';

    /**
     * Config for the Client.
     * Required: api_key
     * 
     * @var array
     */
    private $config;

    /**
     * Config for the Client.
     * Required: api_key
     * 
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->validateConfig($config);

        $this->config = $config;
    }

    /**
     * Make instance of RestApiClient.
     * 
     * @return ClientInterface
     */
    public function restApi(): ClientInterface
    {
        return new GuzzleHttp([
            'base_uri' => 'https://trackapi.thailandpost.co.th',
            'headers' => [
                'accept' => 'application/json',
            ],
        ]);
    }

    /**
     * Make instance for handle webhook.
     * 
     * @return ClientInterface
     */
    public function webhook(): ClientInterface
    {
        return new GuzzleHttp([
            'base_uri' => 'https://trackwebhook.thailandpost.co.th',
            'headers' => [
                'accept' => 'application/json',
            ],
        ]);
    }

    /**
     * Get client config.
     * 
     * @param null $key
     * @return array|mixed|null
     */
    public function getConfig($key = null)
    {
        if (is_null($key)) {
            return $this->config;
        }

        return Arr::get($this->config, $key);
    }

    /**
     * Validate config before create client.
     * 
     * @param array $config
     */
    private function validateConfig(array $config)
    {
        // Check api_key must be set.
        if (!isset($config['api_key']) || empty($config['api_key'])) {
            throw new \InvalidArgumentException("Please specify api_key");
        }
    }
}
