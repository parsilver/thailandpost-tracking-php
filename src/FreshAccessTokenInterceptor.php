<?php

namespace Farzai\ThaiPost;

use Farzai\ThaiPost\Contracts\AccessTokenRepositoryInterface;
use Farzai\ThaiPost\Contracts\EndpointVisitable;
use Farzai\ThaiPost\Contracts\EndpointVisitor;
use Farzai\ThaiPost\Contracts\RequestInterceptor;
use Farzai\ThaiPost\Endpoints\ApiEndpoint;
use Farzai\ThaiPost\Endpoints\WebhookEndpoint;
use Farzai\ThaiPost\Exceptions\AccessTokenException;
use Psr\Http\Message\RequestInterface as PsrRequestInterface;

class FreshAccessTokenInterceptor implements EndpointVisitor, RequestInterceptor
{
    /**
     * The access token repository instance.
     */
    private AccessTokenRepositoryInterface $accessTokenRepository;

    private EndpointVisitable $endpoint;

    /**
     * Create a new interceptor instance.
     */
    public function __construct(EndpointVisitable $endpoint, AccessTokenRepositoryInterface $accessTokenRepository)
    {
        $this->endpoint = $endpoint;
        $this->accessTokenRepository = $accessTokenRepository;
    }

    /**
     * Apply the interceptor to the request.
     */
    public function apply(PsrRequestInterface $request): PsrRequestInterface
    {
        // Try to get the access token from the repository.
        try {
            $accessToken = $this->accessTokenRepository->getToken();
        } catch (AccessTokenException) {
            // If the access token is not found or expired.
            // generate a new access token.
            // And save the access token to the repository.
            $this->accessTokenRepository->saveToken(
                $accessToken = $this->endpoint->accept($this)
            );
        }

        $token = $accessToken->getToken();

        return $request->withHeader(
            'Authorization',
            "Token {$token}"
        );
    }

    /**
     * Generate a new access token for the API endpoint.
     */
    public function generateAccessTokenForApiEndpoint(ApiEndpoint $endpoint): AccessTokenEntity
    {
        $response = $endpoint->generateAccessToken();

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
    public function generateAccessTokenForWebhookEndpoint(WebhookEndpoint $endpoint): AccessTokenEntity
    {
        $response = $endpoint->generateAccessToken();

        $token = $response->json('token');
        $expires = $response->json('expire');

        $accessToken = AccessTokenEntity::fromArray([
            'token' => $token,
            'expires_at' => $expires,
        ]);

        return $accessToken;
    }
}
