<?php

namespace Farzai\ThaiPost;

use Farzai\ThaiPost\Support\Arr;
use GuzzleHttp\Client as GuzzleHttp;
use Psr\Http\Client\ClientInterface;

class Client
{
    const VERSION = '1.0.0';

    /**
     * @var array
     */
    private $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->validateConfig($config);

        $this->config = $config;
    }

    /**
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
     * @param array $config
     */
    private function validateConfig(array $config)
    {
        if (! isset($config['api_key'])) {
            throw new \InvalidArgumentException("Please specify api_key");
        }
    }
}