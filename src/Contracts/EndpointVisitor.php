<?php

namespace Farzai\ThaiPost\Contracts;

use Farzai\ThaiPost\AccessTokenEntity;
use Farzai\ThaiPost\Client;

interface EndpointVisitor
{
    /**
     * Generate a new access token for the API endpoint.
     */
    public function generateAccessTokenForApiEndpoint(Client $client): AccessTokenEntity;

    /**
     * Generate a new access token for the webhook endpoint.
     */
    public function generateAccessTokenForWebhookEndpoint(Client $client): AccessTokenEntity;
}
