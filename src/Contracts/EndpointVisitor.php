<?php

namespace Farzai\ThaiPost\Contracts;

use Farzai\ThaiPost\AccessTokenEntity;
use Farzai\ThaiPost\Endpoints\ApiEndpoint;
use Farzai\ThaiPost\Endpoints\WebhookEndpoint;

interface EndpointVisitor
{
    public function generateAccessTokenForApiEndpoint(ApiEndpoint $endpoint): AccessTokenEntity;

    public function generateAccessTokenForWebhookEndpoint(WebhookEndpoint $endpoint): AccessTokenEntity;
}
