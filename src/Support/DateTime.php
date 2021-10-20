<?php

namespace Farzai\ThaiPost\Support;

use DateTime as BaseDateTime;
use DateTimeZone;

class DateTime extends BaseDateTime
{
    /**
     * @param string $dateTime
     * @param string $format
     * @return BaseDateTime|false
     */
    public static function parseFromAPI(string $dateTime, string $format = "Y-m-d H:i:sT")
    {
        return static::createFromFormat($format, $dateTime);
    }

    /**
     * @param null $tz
     * @return static
     * @throws \Exception
     */
    public static function now($tz = null)
    {
        return new static(null, $tz);
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function isPast()
    {
        return $this->lessThan(static::now());
    }

    /**
     * @param \DateTimeInterface $date
     * @return bool
     */
    public function lessThan($date): bool
    {
        return $this < $date;
    }
}