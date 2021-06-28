<?php namespace Farzai\Tests;

use Farzai\ThaiPost\Auth\ApiToken;
use Farzai\ThaiPost\Auth\Credential;
use Farzai\ThaiPost\Client\Client;
use Farzai\ThaiPost\Client\GuzzleClient;
use Farzai\ThaiPost\Requests\GetItemsRequest;
use Farzai\ThaiPost\Responses\TrackingResponse;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class TrackerTest extends TestCase
{

    public function test_shouldSeeSuccessResponse()
    {
        $client = new Client(new ApiToken("THISISTOKEN"));

        $client->shouldUse(function (Credential $credential) {
            $mock = new MockHandler([
                new Response(
                    200, ['Content-Type' => 'application/json'], $this->getJsonMockup("tracking-response.success")
                ),
            ]);

            $handlerStack = HandlerStack::create($mock);

            return new GuzzleClient($credential, [
                'handler' => $handlerStack,
            ]);
        });

        $request = new GetItemsRequest();

        /** @var TrackingResponse $response */
        $response = $client->execute($request);

        $this->assertBasicResponse($response);

        $this->assertIsArray($response->items);
        $this->assertCount(1, $response->items);

        $this->assertEquals(48, $response->trackCount->count_number);
    }
}