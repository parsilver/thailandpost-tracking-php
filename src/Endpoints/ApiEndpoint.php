<?php

namespace Farzai\ThaiPost\Endpoints;

use Farzai\ThaiPost\Client;
use Farzai\ThaiPost\PendingRequest;
use Farzai\Transport\Contracts\ResponseInterface;

class ApiEndpoint
{
    /**
     * @var \Farzai\ThaiPost\Client
     */
    protected $client;

    /**
     * Create a new api endpoint instance.
     */
    public function __construct(Client $client)
    {
        $this->client = $client;

        $transport = $this->client->getTransport();
        $transport->setUri('https://trackapi.thailandpost.co.th');
    }

    /**
     * Get items by barcode.
     *
     * @param  array<string, mixed>  $params
     */
    public function getItemsByBarcodes(array $params): ResponseInterface
    {
        $defaultParams = [
            'status' => 'all',
            'language' => 'TH',
        ];

        $barcodes = array_filter(array_map('trim', $params['barcode'] ?? []));

        if (empty($barcodes)) {
            throw new \InvalidArgumentException('The barcode is required.');
        }

        $request = $this->makeRequest('POST', '/post/api/v1/track', [
            'body' => array_merge($defaultParams, $params, [
                'barcode' => $barcodes,
            ]),
        ]);

        return $request->acceptJson()->asJson()->send();
    }

    /**
     * Generate a new api token.
     */
    public function getToken(string $token): ResponseInterface
    {
        return $this->makeRequest('POST', '/post/api/v1/authenticate/token')
            ->withToken($token, 'Token')
            ->asJson()
            ->acceptJson()
            ->send();
    }

    /**
     * Get client.
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    protected function makeRequest(
        string $method,
        string $path,
        array $options = []
    ): PendingRequest {
        return new PendingRequest($this->client, $method, $path, $options);
    }
}
