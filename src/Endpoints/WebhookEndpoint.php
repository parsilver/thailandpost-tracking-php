<?php

namespace Farzai\ThaiPost\Endpoints;

use Farzai\ThaiPost\Contracts\AccessTokenRepositoryInterface;
use Farzai\ThaiPost\Repositories\AccessTokenRepository;
use Farzai\ThaiPost\Exceptions\InvalidApiTokenException;
use Farzai\Transport\Contracts\ResponseInterface;
use Psr\Http\Message\RequestInterface as PsrRequestInterface;

class WebhookEndpoint extends AbstractEndpoint
{
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
            ->withInterceptor($this->getRequestInterceptor())
            ->send();
    }

    private function getRequestInterceptor(): callable
    {
        return function (PsrRequestInterface $request) {
            $accessToken = $this->getAuthorzier()->retrieveAccessTokenForWebhook();

            return $request->withHeader('Authorization', "Bearer {$accessToken}");
        };
    }

    /**
     * Get the base uri of the endpoint.
     */
    protected function getUri(): string
    {
        return 'https://trackwebhook.thailandpost.co.th';
    }

    protected function getAccessTokenRepository(): AccessTokenRepositoryInterface
    {
        return new AccessTokenRepository('access-token:webhook', $this->getClient()->getStorage());
    }
}
