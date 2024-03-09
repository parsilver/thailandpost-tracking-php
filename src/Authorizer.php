<?php

namespace Farzai\ThaiPost;

use Farzai\ThaiPost\Contracts\EndpointVisitor;
use Farzai\ThaiPost\Endpoints\ApiEndpoint;
use Farzai\ThaiPost\Endpoints\WebhookEndpoint;

class Authorizer implements EndpointVisitor
{
    /**
     * Generate a new access token for the API endpoint.
     */
    public function generateAccessTokenForApiEndpoint(Client $client): AccessTokenEntity
    {
        $response = (new ApiEndpoint($client))->generateAccessToken();

        $token = $response->json('token');
        $expires = $response->json('expire');

        $accessToken = AccessTokenEntity::fromArray([
            'token' => $token,
            'expires_at' => $expires,
        ]);

        return $accessToken;
    }

    /**
     * Generate a new access token for the webhook endpoint.
     */
    public function generateAccessTokenForWebhookEndpoint(Client $client): AccessTokenEntity
    {
        $response = (new WebhookEndpoint($client))->generateAccessToken();

        $token = $response->json('token');
        $expires = $response->json('expire');

        $accessToken = AccessTokenEntity::fromArray([
            'token' => $token,
            'expires_at' => $expires,
        ]);

        return $accessToken;
    }
}
