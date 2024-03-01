<?php

namespace Farzai\ThaiPost;

use Farzai\Support\Carbon;
use Farzai\ThaiPost\Contracts\AccessTokenRepositoryInterface;
use Farzai\ThaiPost\Endpoints\ApiEndpoint;

class Authorizer
{
    public function __construct(
        protected Client $client,
        protected AccessTokenRepositoryInterface $accessTokenRepository
    ) {
        $this->client = $client;
        $this->accessTokenRepository = $accessTokenRepository;
    }

    /**
     * Generate a new api token.
     */
    public function retrieveToken(): string
    {
        $token = $this->getTokenFromStorage();

        if (!$token) {
            $api = new ApiEndpoint($this->client);

            $apiToken = $this->client->getConfig("token");

            $response = $api->generateAccessToken($apiToken);

            $plainToken = $response->json("token");

            // Parse from format: "2019-09-28 10:18:20+07:00"
            $expiresAt = Carbon::parse($response->json("expire"));

            $token = new AccessTokenEntity(
                $plainToken,
                $expiresAt->toDateTimeImmutable()
            );

            $this->accessTokenRepository->saveToken($token);
        }

        return $token;
    }

    /**
     * Get token from storage.
     */
    public function getTokenFromStorage(): ?string
    {
        try {
            $token = $this->accessTokenRepository->getToken();

            $expires = Carbon::parse($token->expiresAt());

            if ($expires->isFuture()) {
                return $token->getToken();
            }
        } catch (\Throwable $th) {
            //
        }

        return null;
    }
}
