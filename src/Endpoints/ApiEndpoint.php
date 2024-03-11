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
     * Track by barcode.
     *
     * @param  array  $params
     * @return \Farzai\Transport\Contracts\ResponseInterface
     *
     * @requestBody [
     *    "status" => "all",
     *    "language" => "TH",
     *    "barcode" => [
     *        "ED852942182TH",
     *        "ED852942183TH",
     *        "ED852942184TH"
     *    ]
     * ]
     *
     * @responseBody {
     *    "response": {
     *        "items": {
     *            "ED852942182TH": [
     *                {
     *                    "barcode": "ED852942182TH",
     *                    "status": "103",
     *                    "status_description": "รับฝาก",
     *                    "status_date": "19/07/2562 18:12:26+07:00",
     *                    "location": "คต.กาดสวนแก้ว",
     *                    "postcode": "00131",
     *                    "delivery_status": null,
     *                    "delivery_description": null,
     *                    "delivery_datetime": null,
     *                    "receiver_name": null,
     *                    "signature": null,
     *                    "delivery_officer_name": null,
     *                    "delivery_officer_tel": null,
     *                    "office_name": null,
     *                    "office_tel": null,
     *                    "call_center_tel": "1545"
     *                },
     *
     *                ...
     *            ]
     *        },
     *        "track_count": {
     *            "track_date": "27/08/2562",
     *            "count_number": 48,
     *            "track_count_limit": 1500
     *        }
     *    },
     *    "message": "successful",
     *    "status": true
     * }
     */
    public function getItemsByBarcodes(array $params): ResponseInterface
    {
        $defaultParams = [
            'status' => 'all',
            'language' => 'TH',
        ];

        $barcodes = is_string($params['barcode']) ? explode(',', $params['barcode']) : $params['barcode'];
        $barcodes = array_filter(array_map('trim', (array)$barcodes));

        $request = $this->makeRequest('POST', '/post/api/v1/track', [
            'body' => array_merge($defaultParams, $params, [
                'barcode' => $barcodes,
            ]),
        ]);

        return $request
            ->acceptJson()
            ->asJson()
            ->withInterceptor($this->getFreshAccessTokenInterceptor())
            ->send();
    }

    /**
     * Track by receipt.
     *
     * @param  array  $params
     * @return \Farzai\Transport\Contracts\ResponseInterface
     *
     * @requestBody [
     *    "status" => "all",
     *    "language" => "TH",
     *    "receiptNo" => [
     *        "361101377131",
     *        "361101377132",
     *        "361101377133"
     *    ]
     * ]
     *
     * @responseBody {
     *    "response": {
     *        "receipts": {
     *            "361101377131": {
     *                "EB000000001TH": [
     *                    {
     *                        "barcode": "EB000000001TH",
     *                        "status": "103",
     *                        "status_description": "รับฝาก",
     *                        "status_date": "04/11/2563 13:20:00+07:00",
     *                        "location": "บาเจาะ",
     *                        "postcode": "96170",
     *                        "delivery_status": null,
     *                        "delivery_description": null,
     *                        "delivery_datetime": null,
     *                        "receiver_name": null,
     *                        "signature": null,
     *                        "delivery_officer_name": null,
     *                        "delivery_officer_tel": null,
     *                        "office_name": null,
     *                        "office_tel": null,
     *                        "call_center_tel": "1545"
     *                    },
     *                    ...
     *                ]
     *            }
     *        },
     *        "track_count": {
     *            "track_date": "28/12/2564",
     *            "count_number": 0,
     *            "track_count_limit": 0
     *        }
     *    },
     *    "message": "successful",
     *    "status": true
     * }
     */
    public function getItemsByReceipts(array $params): ResponseInterface
    {
        $defaultParams = [
            'status' => 'all',
            'language' => 'TH',
        ];

        $receipts = is_string($params['receiptNo']) ? explode(',', $params['receiptNo']) : $params['receiptNo'];
        $receipts = array_filter(
            array_map('trim', (array) $receipts)
        );

        $request = $this->makeRequest('POST', '/post/api/v1/receipt/track', [
            'body' => array_merge($defaultParams, $params, [
                'receiptNo' => $receipts,
            ]),
        ]);

        return $request
            ->acceptJson()
            ->asJson()
            ->withInterceptor($this->getFreshAccessTokenInterceptor())
            ->send();
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
     * Accept the visitor.
     *
     * @param  \Farzai\ThaiPost\Contracts\EndpointVisitor  $visitor
     *
     * @return \Farzai\ThaiPost\AccessTokenEntity
     */
    public function accept(EndpointVisitor $visitor)
    {
        return $visitor->generateAccessTokenForApiEndpoint($this->getClient());
    }

    /**
     * Get the fresh access token interceptor.
     *
     * @return \Farzai\ThaiPost\FreshAccessTokenInterceptor
     */
    private function getFreshAccessTokenInterceptor()
    {
        return new FreshAccessTokenInterceptor(
            $this->getClient()->getAccessTokenRepository(),
            $this,
        );
    }
}
