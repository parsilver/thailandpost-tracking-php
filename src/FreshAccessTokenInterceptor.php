<?php

namespace Farzai\ThaiPost;

use Farzai\ThaiPost\Contracts\RequestInterceptor;
use Farzai\ThaiPost\Exceptions\AccessTokenException;
use Psr\Http\Message\RequestInterface as PsrRequestInterface;
use Farzai\ThaiPost\Endpoints\ApiEndpoint;

class FreshAccessTokenInterceptor implements RequestInterceptor
{
    public function __construct(protected Client $client)
    {
        //
    }

    /**
     * Apply the interceptor to the request.
     *
     * @param PsrRequestInterface $request
     * @return PsrRequestInterface
     */
    public function apply(PsrRequestInterface $request): PsrRequestInterface
    {
        $repository = $this->client->getAccessTokenRepository();

        // Try to get the access token from the repository.
        try {
            $accessToken = $repository->getToken();
        } catch (AccessTokenException) {
            // If the access token is not found or expired.
            // Generate a new access token.
            $response = (new ApiEndpoint($this->client))->generateAccessToken();

            $token = $response->json("token");
            $expires = $response->json("expire");

            // Save the access token to the repository.
            $repository->saveToken(
                $accessToken = AccessTokenEntity::fromArray([
                    "token" => $token,
                    "expires_at" => $expires,
                ])
            );
        }

        return $request->withHeader(
            "Authorization",
            "Token {$accessToken->getToken()}"
        );
    }
}
