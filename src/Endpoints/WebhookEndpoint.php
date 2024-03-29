<?php

namespace Farzai\ThaiPost\Endpoints;

use Farzai\ThaiPost\Client;
use Farzai\ThaiPost\Exceptions\InvalidApiTokenException;
use Farzai\ThaiPost\Repositories\AccessTokenRepository;
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
     * @param  array{
     *      barcode: string|array<string>,
     *      status?: string,
     *      language?: string,
     *      req_previous_status?: bool,
     * }  $params
     *
     * @responseBody {
     *      "response": {
     *          "items": [
     *              {
     *                  "barcode": "EY145587896TH",
     *                  "status": true
     *              },
     *          ],
     *          "track_count": {
     *              "track_date": "04/09/2562",
     *              "count_number": 4,
     *              "track_count_limit": 1000
     *          }
     *      },
     *      "message": "successful",
     *      "status": true
     * }
     */
    public function subscribeByBarcodes(array $params): ResponseInterface
    {
        $defaultParams = [
            'status' => 'all',
            'language' => 'TH',
            'req_previous_status' => true,
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

    /**
     * Subscribe by receipts.
     *
     * @param  array{
     *      receiptNo: string|array<string>,
     *      status?: string,
     *      language?: string,
     *      req_previous_status?: bool,
     * }  $params
     *
     * @responseBody {
     *      "response": {
     *          "receipts": {
     *              "361101377131": [
     *                  "EB000000001TH"
     *              ],
     *          },
     *          "track_count": {
     *              "track_date": "8/8/2564",
     *              "count_number": 10,
     *              "track_count_limit": 100000
     *          }
     *      },
     *      "message": "successful",
     *      "status": true
     * }
     */
    public function subscribeByReceipts(array $params): ResponseInterface
    {
        $defaultParams = [
            'status' => 'all',
            'language' => 'TH',
            'req_previous_status' => true,
        ];

        $receipts = is_string($params['receiptNo']) ? explode(',', $params['receiptNo']) : $params['receiptNo'];
        $receipts = array_filter(
            array_map('trim', (array) $receipts)
        );

        return $this->makeRequest('POST', '/post/api/v1/hook/receipt')
            ->withBody(array_merge($defaultParams, $params, [
                'receiptNo' => $receipts,
            ]))
            ->asJson()
            ->acceptJson()
            ->withInterceptor($this->getRequestInterceptor())
            ->send();
    }

    /**
     * Subscribe by profile.
     *
     * @param  array{
     *      fullName: string,
     *      telephone: string,
     *      email?: string,
     *      nickname?: string,
     * }  $params
     *
     * @responseBody {
     *      "status": 1,
     *      "message": "successful",
     *      "uid": "cmVmY29kZS1tb2NrLTAwMQ==",
     *      "ref": "E9nTJz"
     * }
     */
    public function subscribeByProfile(array $params): ResponseInterface
    {
        return $this->makeRequest('POST', '/post/api/v1/user-register/user-profile')
            ->withBody($params)
            ->asJson()
            ->acceptJson()
            ->withInterceptor($this->getRequestInterceptor())
            ->send();
    }

    /**
     * Unsubscribe by profile.
     *
     * @param  array{
     *      uid: string,
     *      ref: string,
     * }  $params
     *
     * @responseBody {
     *      "status": 1,
     *      "message": "successful"
     * }
     */
    public function unsubscribeProfile(array $params): ResponseInterface
    {
        $uid = $params['uid'] ?? null;
        $ref = $params['ref'] ?? null;

        if (empty($uid) || empty($ref)) {
            throw new \InvalidArgumentException('The uid and ref are required.');
        }

        return $this->makeRequest("DELETE", "/post/api/v1/unsubscribe/{$uid}?ref={$ref}")
            ->acceptJson()
            ->withInterceptor($this->getRequestInterceptor())
            ->send();
    }

    /*
     * Get the request interceptor.
     *
     * @return callable<PsrRequestInterface>
     */
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

    public function __construct(Client $client)
    {
        parent::__construct($client, new AccessTokenRepository(
            'access-token:webhook',
            $client->getStorage(),
        ));
    }
}
