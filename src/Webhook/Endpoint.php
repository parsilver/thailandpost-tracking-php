<?php

namespace Farzai\ThaiPost\Webhook;

use Psr\Http\Client\ClientInterface;
use Farzai\ThaiPost\AbstractEndpoint;
use Farzai\ThaiPost\Client;
use Farzai\ThaiPost\Entity\TokenEntity;
use Farzai\ThaiPost\Response\Response;
use Farzai\ThaiPost\Response\ResponseInterface;
use Farzai\ThaiPost\Webhook\Auth\SessionToken;

class Endpoint extends AbstractEndpoint
{

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        parent::__construct($client);

        $this->setTokenStore(new SessionToken());
    }

    /**
     * @param Requests\GetToken $request
     * @return ResponseInterface
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function getAuthToken(Requests\GetToken $request): ResponseInterface
    {
        return new Response($this->getTransporter()->sendRequest($request->getRequest()));
    }

    /**
     * @param Requests\SubscribeByBarcode $request
     * @return ResponseInterface
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function subscribeByBarcode(Requests\SubscribeByBarcode $request): ResponseInterface
    {
        return $this->handleWithAuthorization($request, function ($request) {
            return new Response($this->getTransporter()->sendRequest($request->getRequest()));
        });
    }

    /**
     * @param Client $client
     * @return TokenEntity
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    protected function fetchToken(Client $client): TokenEntity
    {
        $tokenResponse = $this->getAuthToken(new Requests\GetToken($client->getConfig('api_key')));

        return TokenEntity::fromArray(
            $tokenResponse->json()
        );
    }

    /**
     * @param Client $client
     * @return ClientInterface
     */
    protected function getHttpClient(Client $client): ClientInterface
    {
        return $client->webhook();
    }
}