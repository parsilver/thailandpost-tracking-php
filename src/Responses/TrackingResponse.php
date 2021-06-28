<?php namespace Farzai\ThaiPost\Responses;

use Farzai\ThaiPost\Requests\Request;
use Farzai\ThaiPost\Entity\CodeEntity;
use Farzai\ThaiPost\Entity\TrackCountEntity;
use Psr\Http\Message\ResponseInterface;

/**
 * Class TrackingResponse
 * @package Farzai\ThaiPost\Responses
 *
 * @property-read TrackCountEntity|null $trackCount
 */
class TrackingResponse extends BasicResponse
{
    /**
     * @var CodeEntity[][]
     */
    public $items;

    /**
     * @var TrackCountEntity|null
     */
    public $trackCount;

    /**
     * TrackingResponse constructor.
     * @param Request $request
     * @param ResponseInterface $response
     */
    public function __construct(Request $request, ResponseInterface $response)
    {
        parent::__construct($request, $response);

        if ($this->isStatusSuccess() && !$this->items) {
            $this->parseEntities();
        }
    }

    /**
     * @param string $code
     * @return CodeEntity[]
     */
    public function fromCode(string $code)
    {
        return $this->items[$code] ?? [];
    }


    protected function parseEntities()
    {
        $this->items = [];
        $this->trackCount = TrackCountEntity::fromArray(
            $this->json('response.track_count')
        );

        foreach ($this->json('response.items') as $code => $item) {
            $this->items[$code][] = CodeEntity::fromArray($item);
        }
    }
}