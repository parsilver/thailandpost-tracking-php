<?php namespace Farzai\Tests;

use Farzai\ThaiPost\Responses\BasicResponse;
use Farzai\ThaiPost\Responses\Response;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
    /**
     * @param string $filename
     * @return string|null
     */
    protected function getJsonMockup(string $filename): ?string
    {
        return file_get_contents(__DIR__."/json/{$filename}.json") ?: null;
    }

    /**
     * @param BasicResponse $response
     */
    protected function assertBasicResponse(Response $response)
    {
        $this->assertTrue($response->ok());
        $this->assertTrue(is_bool($response->status));
        $this->assertNotEmpty($response->message);
    }
}