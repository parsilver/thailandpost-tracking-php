<?php

namespace Farzai\ThaiPost;

use Farzai\ThaiPost\Contracts\AccessTokenRepositoryInterface;
use Farzai\ThaiPost\Contracts\EndpointVisitable;
use Farzai\ThaiPost\Contracts\RequestInterceptor;
use Farzai\ThaiPost\Exceptions\AccessTokenException;
use Psr\Http\Message\RequestInterface as PsrRequestInterface;

class FreshAccessTokenInterceptor implements RequestInterceptor
{
    /**
     * The endpoint visitable instance.
     */
    private EndpointVisitable $endpointVisitable;

    /**
     * The access token repository instance.
     */
    private AccessTokenRepositoryInterface $accessTokenRepository;

    /**
     * Create a new interceptor instance.
     */
    public function __construct(
        AccessTokenRepositoryInterface $accessTokenRepository,
        EndpointVisitable $endpointVisitable,
    ) {
        $this->endpointVisitable = $endpointVisitable;
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

            // If the access token is not found, we need to generate a new one.
            $this->accessTokenRepository->saveToken(
                // Accept the visitor to generate a new access token.
                $accessToken = $this->endpointVisitable->accept(new Authorizer()),
            );
        }

        $token = $accessToken->getToken();

        return $request->withHeader(
            'Authorization',
            "Token {$token}"
        );
    }
}
