<?php namespace Farzai\ThaiPost\RestApi\Entity;

use Farzai\ThaiPost\Entity\AbstractEntity;

/**
 * Class CodeEntity
 * @package Farzai\ThaiPost\Entity
 *
 * @property-read string barcode // Example: "ED852942182TH"
 * @property-read string status
 * @property-read string status_description
 * @property-read string status_date // Example: "20/07/2562 15:12:15+07:00"
 * @property-read string location
 * @property-read string postcode
 * @property-read integer|null delivery_status
 * @property-read string|null delivery_description
 * @property-read string|null delivery_datetime
 * @property-read string|null signature
 * @property-read string|null message
 * @property-read string|null receiver_name
 */
class CodeEntity extends AbstractEntity
{
    //
}