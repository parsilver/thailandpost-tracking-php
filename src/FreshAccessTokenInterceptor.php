<?php

namespace Farzai\ThaiPost;

use Farzai\ThaiPost\Contracts\RequestInterceptor;
use Psr\Http\Message\RequestInterface as PsrRequestInterface;

class FreshAccessTokenInterceptor implements RequestInterceptor
{
    public function __construct(
        protected Client $client
    ) {
    }

    public function apply(PsrRequestInterface $request): PsrRequestInterface
    {
        $repository = $this->client->getAccessTokenRepository();

        $accessToken = $repository->getToken();

        return $request->withHeader('Authorization', "Token {$accessToken}");
    }
}
