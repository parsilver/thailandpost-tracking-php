<?php

namespace Farzai\ThaiPost\Webhook;

use Psr\Http\Client\ClientInterface;
use Farzai\ThaiPost\AbstractEndpoint;
use Farzai\ThaiPost\Client;
use Farzai\ThaiPost\Entity\TokenEntity;
use Farzai\ThaiPost\Response\Response;
use Farzai\ThaiPost\Response\ResponseInterface;

class Endpoint extends AbstractEndpoint
{

    /**
     * @param Requests\GetToken $request
     * @return ResponseInterface
     */
    public function getAuthToken(Requests\GetToken $request): ResponseInterface
    {
        return new Response($this->getTransporter()->sendRequest($request->getRequest()));
    }

    /**
     * @param Requests\SubscribeByBarcode $request
     * @return ResponseInterface
     * @throws \Farzai\ThaiPost\Exception\InvalidApiTokenException
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
     * @return TokenEntity|null
     */
    protected function fetchToken(Client $client)
    {
        $tokenResponse = $this->getAuthToken(new Requests\GetToken($client->getConfig('api_key')));

        if ($tokenResponse->isOk()) {
            return TokenEntity::fromArray($tokenResponse->json());
        }
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