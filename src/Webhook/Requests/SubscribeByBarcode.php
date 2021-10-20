<?php

namespace Farzai\ThaiPost\Webhook\Requests;

use Farzai\ThaiPost\Request;
use Psr\Http\Message\RequestInterface as MessageRequestInterface;

class SubscribeByBarcode extends Request
{
    private $payload = [
        'barcode' => [],
        'status' => 'all',
        'language' => 'TH',
        'req_previous_status' => false,
    ];

    /**
     * @param array $barcodes
     */
    public function __construct(array $barcodes = [])
    {
        $this->method = 'POST';
        $this->path = '/post/api/v1/hook';
        $this->headers['Content-Type'] = 'application/json';

        $this->setBarcodes($barcodes);
    }

    /**
     * @param array $barcodes
     * @return $this
     */
    public function setBarcodes(array $barcodes)
    {
        $this->payload['barcode'] = $barcodes;

        return $this;
    }


    /**
     * @param string $language
     * @return $this
     */
    public function setLanguage(string $language)
    {
        $this->payload['language'] = $language;

        return $this;
    }


    /**
     * @param string|numeric $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->payload['status'] = (string)$status;

        return $this;
    }

    /**
     * @param bool $previousStatus
     * @return $this
     */
    public function withPreviousStatus($previousStatus = true)
    {
        $this->payload['req_previous_status'] = $previousStatus;

        return $this;
    }

    /**
     * @return MessageRequestInterface
     */
    public function getRequest(): MessageRequestInterface
    {
        $this->body = json_encode($this->payload);

        return parent::getRequest();
    }
}