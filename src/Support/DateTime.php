<?php

namespace Farzai\ThaiPost\Support;

use DateTime as BaseDateTime;
use DateTimeInterface;
use DateTimeZone;

class DateTime extends BaseDateTime
{

    const DEFAULT_TIME_ZONE = 'Asia/Bangkok';

    /**
     * @param string $dateTime
     * @param string $format
     * @return \Farzai\ThaiPost\Support\DateTime
     */
    public static function parseFromAPI(string $dateTime, string $format = "Y-m-d H:i:sT")
    {
        // '04/11/2563 13:20:00+07:00'
        if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4}) (\d{2}):(\d{2}):(\d{2})[+-](\d{2}):(\d{2})$/', $dateTime, $matches)) {
            $dateTime = sprintf('%s-%s-%s %s:%s:%s', $matches[3] - 543, $matches[2], $matches[1], $matches[4], $matches[5], $matches[6]);

            return new static($dateTime, new DateTimeZone('+' . str_pad($matches[7], 2, '0', STR_PAD_LEFT)));
        }

        return self::createFromFormat($format, $dateTime);
    }

    /**
     * @param null $tz
     * @return static
     * @throws \Exception
     */
    public static function now($tz = null)
    {
        return new static('now', $tz);
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
