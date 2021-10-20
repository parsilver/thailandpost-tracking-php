<?php

namespace Farzai\ThaiPost\Requests;

use Psr\Http\Message\RequestInterface as MessageRequestInterface;


class GetItemsByBarcode extends Request
{
    /**
     * @var string
     */
    private $status = 'all';

    /**
     * @var string
     */
    private $language = 'TH';

    /**
     * @var array
     */
    private $barcodes;

    /**
     * @param array $barcodes
     */
    public function __construct(array $barcodes = [])
    {
        $this->method = 'POST';
        $this->path = '/post/api/v1/track';
        $this->headers['Content-Type'] = 'application/json';

        $this->setBarcodes($barcodes);
    }

    /**
     * @param array $barcodes
     * @return $this
     */
    public function setBarcodes(array $barcodes)
    {
        $this->barcodes = $barcodes;

        return $this;
    }


    /**
     * @param string $language
     * @return $this
     */
    public function setLanguage(string $language)
    {
        $this->language = $language;

        return $this;
    }


    /**
     * @param string|numeric $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = (string)$status;

        return $this;
    }

    /**
     * @return MessageRequestInterface
     */
    public function getRequest(): MessageRequestInterface
    {
        $this->body = json_encode([
            'barcode' => $this->barcodes,
            'status' => $this->status,
            'language' => $this->language,
        ]);

        return parent::getRequest();
    }
}