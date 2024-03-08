<?php

namespace Farzai\ThaiPost\Endpoints;

use Farzai\ThaiPost\Contracts\EndpointVisitable;
use Farzai\ThaiPost\Contracts\EndpointVisitor;
use Farzai\ThaiPost\Exceptions\InvalidApiTokenException;
use Farzai\ThaiPost\FreshAccessTokenInterceptor;
use Farzai\Transport\Contracts\ResponseInterface;

class WebhookEndpoint extends AbstractEndpoint implements EndpointVisitable
{
    /**
     * Get the base uri of the endpoint.
     */
    public function getUri(): string
    {
        return 'https://trackwebhook.thailandpost.co.th';
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
     * Subscribe by barcodes.
     *
     * @param  array<string, mixed>  $params
     */
    public function subscribeByBarcodes(array $params): ResponseInterface
    {
        $defaultParams = [
            'status' => 'all',
            'language' => 'TH',
            'req_previous_status' => false,
        ];

        $barcodes = array_filter(
            array_map('trim', (array) $params['barcode'] ?? [])
        );

        return $this->makeRequest('POST', '/post/api/v1/hook')
            ->withBody(array_merge($defaultParams, $params, [
                'barcode' => $barcodes,
            ]))
            ->asJson()
            ->acceptJson()
            ->withInterceptor(new FreshAccessTokenInterceptor($this, $this->getClient()->getAccessTokenRepository()))
            ->send();
    }

    /**
     * Accept the visitor.
     */
    public function accept(EndpointVisitor $visitor)
    {
        return $visitor->generateAccessTokenForWebhookEndpoint($this);
    }
}
