<?php

namespace Farzai\ThaiPost;

use Farzai\ThaiPost\Contracts\AccessTokenRepositoryInterface;
use Farzai\ThaiPost\Endpoints\ApiEndpoint;
use Farzai\ThaiPost\Endpoints\WebhookEndpoint;
use Farzai\ThaiPost\Exceptions\AccessTokenException;

class Authorizer
{
    public function __construct(
        private Client $client,
        private AccessTokenRepositoryInterface $accessTokenRepository,
    ) {
    }

    public function retrieveAccessTokenForApi(): AccessTokenEntity
    {
        try {
            $token = $this->accessTokenRepository->getToken();
        } catch (AccessTokenException) {
            $response = (new ApiEndpoint($this->client))->generateAccessToken();

            $token = AccessTokenEntity::fromArray([
                'token' => $response->json('token'),
                'expires_at' => $response->json('expire'),
            ]);

            $this->accessTokenRepository->saveToken($token);
        }

        return $token;
    }

    public function retrieveAccessTokenForWebhook(): AccessTokenEntity
    {
        try {
            $token = $this->accessTokenRepository->getToken();
        } catch (AccessTokenException) {
            $response = (new WebhookEndpoint($this->client))->generateAccessToken();

            $token = AccessTokenEntity::fromArray([
                'token' => $response->json('token'),
                'expires_at' => $response->json('expire'),
            ]);

            $this->accessTokenRepository->saveToken($token);
        }

        return $token;
    }
}
