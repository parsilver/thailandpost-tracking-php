<?php namespace Farzai\ThaiPost\Requests;

use Farzai\ThaiPost\Responses\TrackingResponse;
use Psr\Http\Message\ResponseInterface;

/**
 * Class GetItemsRequest
 * @package Farzai\ThaiPost\Requests
 */
class GetItemsRequest extends AbstractRequest
{
    /**
     * @var string
     */
    private $language = 'EN';

    /**
     * @var string
     */
    private $status = 'all';

    /**
     * @var array
     */
    private $codes;

    /**
     * TrackingRequest constructor.
     */
    public function __construct(array $codes = [])
    {
        parent::__construct("https://trackapi.thailandpost.co.th/post/api/v1/track", "POST");

        $this->setCodes($codes);
    }

    /**
     * @param array $codes
     * @return $this
     */
    public function setCodes(array $codes)
    {
        $this->codes = $codes;

        return $this;
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @param string $language // Supported TH,EN,CN
     * @return $this
     */
    public function setLanguage(string $language)
    {
        $this->language = strtoupper($language);

        return $this;
    }

    /**
     * @return array
     */
    public function payload()
    {
        return [
            'status' => $this->status,
            'language' => $this->language,
            'barcode' => $this->codes,
        ];
    }

    /**
     * @param Request $request
     * @param ResponseInterface $response
     * @return TrackingResponse
     */
    public function toResponse(Request $request, ResponseInterface $response)
    {
        return new TrackingResponse($request, $response);
    }
}