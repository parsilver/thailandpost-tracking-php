<?php

namespace Farzai\ThaiPost\Endpoints;

use Farzai\ThaiPost\Contracts\EndpointVisitable;
use Farzai\ThaiPost\Contracts\EndpointVisitor;
use Farzai\ThaiPost\Exceptions\InvalidApiTokenException;
use Farzai\ThaiPost\FreshAccessTokenInterceptor;
use Farzai\Transport\Contracts\ResponseInterface;

class ApiEndpoint extends AbstractEndpoint implements EndpointVisitable
{
    /**
     * Get the base uri of the endpoint.
     */
    public function getUri(): string
    {
        return 'https://trackapi.thailandpost.co.th';
    }

    /**
     * Generate a new access token.
     */
    public function generateAccessToken(): ResponseInterface
    {
        return $this->makeRequest('POST', '/post/api/v1/authenticate/token')
            ->withToken($this->client->getConfig('token'), 'Token')
            ->asJson()
            ->acceptJson()
            ->send()
            ->throw(function ($response) {
                if ($response->getStatusCode() === 401) {
                    throw new InvalidApiTokenException();
                }
            });
    }

    /**
     * Track by barcode.
     *
     * @param  array<string, mixed>  $params
     */
    public function trackByBarcodes(array $params): ResponseInterface
    {
        $defaultParams = [
            'status' => 'all',
            'language' => 'TH',
        ];

        $barcodes = array_filter(
            array_map('trim', (array) $params['barcode'] ?? [])
        );

        $request = $this->makeRequest('POST', '/post/api/v1/track', [
            'body' => array_merge($defaultParams, $params, [
                'barcode' => $barcodes,
            ]),
        ]);

        return $request
            ->acceptJson()
            ->asJson()
            ->withInterceptor(new FreshAccessTokenInterceptor($this, $this->getClient()->getAccessTokenRepository()))
            ->send();
    }

    /**
     * Accept the visitor.
     */
    public function accept(EndpointVisitor $visitor)
    {
        return $visitor->generateAccessTokenForApiEndpoint($this);
    }
}
