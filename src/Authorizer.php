<?php

namespace Farzai\ThaiPost;

use Farzai\ThaiPost\Contracts\AccessTokenRepositoryInterface;
use Farzai\ThaiPost\Endpoints\ApiEndpoint;
use Farzai\ThaiPost\Endpoints\WebhookEndpoint;
use Farzai\ThaiPost\Exceptions\AccessTokenException;

class Authorizer
{
    private Client $client;

    private AccessTokenRepositoryInterface $accessTokenRepository;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->accessTokenRepository = $client->getAccessTokenRepository();
    }

    public function retrieveAccessTokenForApi(): AccessTokenEntity
    {
        try {
            $token = $this->accessTokenRepository->getToken();
        } catch (AccessTokenException) {
            $this->accessTokenRepository->saveToken(
                $token = $this->generateAccessTokenForApiEndpoint()
            );
        }

        return $token;
    }

    public function retrieveAccessTokenForWebhook(): AccessTokenEntity
    {
        try {
            $token = $this->accessTokenRepository->getToken();
        } catch (AccessTokenException) {
            $this->accessTokenRepository->saveToken(
                $token = $this->generateAccessTokenForWebhookEndpoint()
            );
        }

        return $token;
    }


    /**
     * Generate a new access token for the API endpoint.
     */
    private function generateAccessTokenForApiEndpoint(): AccessTokenEntity
    {
        $response = (new ApiEndpoint($this->client))->generateAccessToken();

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
    private function generateAccessTokenForWebhookEndpoint(): AccessTokenEntity
    {
        $response = (new WebhookEndpoint($this->client))->generateAccessToken();

        $token = $response->json('token');
        $expires = $response->json('expire');

        $accessToken = AccessTokenEntity::fromArray([
            'token' => $token,
            'expires_at' => $expires,
        ]);

        return $accessToken;
    }
}
