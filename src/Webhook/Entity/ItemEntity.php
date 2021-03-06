<?php

namespace Farzai\ThaiPost\Webhook\Entity;

use Farzai\ThaiPost\Entity\AbstractEntity;

/**
 * @property-read string barcode // Example: "ED852942182TH"
 * @property-read string status
 * @property-read string status_description
 * @property-read string status_date // Example: "20/07/2562 15:12:15+07:00"
 * @property-read string location
 * @property-read string postcode
 * @property-read integer|null delivery_status
 * @property-read string|null delivery_description
 * @property-read string|null delivery_datetime
 * @property-read string|null receiver_name
 * @property-read string|null signature
 */
class ItemEntity extends AbstractEntity
{

}