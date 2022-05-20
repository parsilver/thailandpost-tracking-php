<?php

namespace Farzai\Tests;

use Farzai\ThaiPost\Support\DateTime;
use DateTime as BaseDateTime;

class DateTimeTest extends TestCase
{

    public function test_should_return_current_date_time()
    {
        $dateTime = new DateTime();

        $this->assertInstanceOf(BaseDateTime::class, $dateTime);

        $this->assertEquals(date('Y-m-d H:i:sT'), $dateTime->format('Y-m-d H:i:sT'));

        $this->assertInstanceOf(DateTime::class, DateTime::now());

        $this->assertEquals(date('Y-m-d H:i:sT'), DateTime::now()->format('Y-m-d H:i:sT'));
    }


    public function test_should_parse_date_time_from_api_success()
    {
        $dateTime = DateTime::parseFromAPI('04/11/2563 13:20:00+07:00');

        $this->assertInstanceOf(DateTime::class, $dateTime);
        $this->assertInstanceOf(BaseDateTime::class, $dateTime);

        $this->assertEquals('2020-11-04 13:20:00GMT+0700', $dateTime->format('Y-m-d H:i:sT'));
    }
}
