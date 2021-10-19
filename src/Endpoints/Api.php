<?php

namespace Farzai\ThaiPost\Endpoints;

use Farzai\ThaiPost\Auth\SessionStore;
use Farzai\ThaiPost\Auth\TokenStoreInterface;
use Farzai\ThaiPost\Client;
use Farzai\ThaiPost\Response\Response;
use Farzai\ThaiPost\Response\ResponseInterface;
use Farzai\ThaiPost\Transporter;
use Farzai\ThaiPost\Requests;

class Api implements EndpointInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var TokenStoreInterface
     */
    private $tokenStore;

    /**
     * @param Client $client
     * @param TokenStoreInterface|null $tokenStore
     */
    public function __construct(Client $client, TokenStoreInterface $tokenStore = null)
    {
        $this->client = $client;
        $this->tokenStore = $tokenStore ?: new SessionStore();
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
     * @param Requests\GetItemsByBarcode $request
     * @return ResponseInterface
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function getItemsByBarcode(Requests\GetItemsByBarcode $request): ResponseInterface
    {
        return $this->handleWithAuthorization($request, function ($request) {
            return new Response($this->getTransporter()->sendRequest($request->getRequest()));
        });
    }

    /**
     * @return Transporter
     */
    public function getTransporter(): Transporter
    {
        $transporter = new Transporter($this->client->getHttpClient());

        $token = $this->tokenStore->get();

        if ($token) {
            $transporter->setHeader('Authorization', "Token {$token}");
        }

        return $transporter;
    }

    /**
     * @param Requests\Request $request
     * @param callable $handler
     * @return mixed
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    private function handleWithAuthorization(Requests\Request $request, callable $handler)
    {
        $response = $handler($request);

        if ($response->getResponse()->getStatusCode() === 403) {
            $apiKey = $this->client->getConfig('api_key');

            $tokenResponse = $this->getAuthToken(new Requests\GetToken($apiKey));

            $this->tokenStore->store($tokenResponse->json('token'));
        }

        return $handler($request);
    }
}