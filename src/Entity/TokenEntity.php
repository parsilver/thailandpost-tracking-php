<?php

namespace Farzai\ThaiPost\Entity;

use Farzai\ThaiPost\Support\DateTime;

/**
 * @property-read string expire // Example: "2019-09-28 10:18:20+07:00"
 * @property-read string token
 */
class TokenEntity extends AbstractEntity
{
    /**
     * @return bool
     */
    public function isExpired()
    {
        return DateTime::parseFromAPI($this->expire)->isPast();
    }
}