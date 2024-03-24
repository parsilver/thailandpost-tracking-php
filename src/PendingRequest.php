<?php

namespace Farzai\ThaiPost;

use Farzai\ThaiPost\Contracts\RequestInterceptor;
use Farzai\Transport\Contracts\ResponseInterface;
use Farzai\Transport\Request;
use Farzai\Transport\Response;
use Psr\Http\Message\RequestInterface as PsrRequestInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class PendingRequest
{
    public Client $client;

    public string $method;

    public string $path;

    public array $options;

    /**
     * The request interceptors.
     *
     * @var array<\Farzai\ThaiPost\Contracts\RequestInterceptor>
     */
    public $interceptors = [];

    /**
     * Create a new pending request instance.
     *
     * @param  array<string, mixed>  $options
     */
    public function __construct(Client $client, string $method, string $path, array $options = [])
    {
        $this->client = $client;
        $this->method = $method;
        $this->path = $path;
        $this->options = $options;
    }

    /**
     * Set the request method.
     */
    public function method(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Set the request path.
     */
    public function path(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Set the request options.
     *
     * @param  array<string, mixed>  $options
     */
    public function options(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function withInterceptor(RequestInterceptor|callable $interceptor): self
    {
        $this->interceptors[] = $interceptor;

        return $this;
    }

    /**
     * Set the request body.
     */
    public function withBody(mixed $body): self
    {
        $this->options['body'] = $body;

        return $this;
    }

    /**
     * Set the request query.
     *
     * @param  array<string, mixed>  $query
     */
    public function withQuery(array $query): self
    {
        $this->options['query'] = $query;

        return $this;
    }

    /**
     * Set the request headers.
     *
     * @param  array<string, string>  $headers
     */
    public function withHeaders(array $headers): self
    {
        $this->options['headers'] = array_merge($this->options['headers'] ?? [], $headers);

        return $this;
    }

    /**
     * Set a request header.
     */
    public function withHeader(string $key, string $value): self
    {
        $this->options['headers'][$key] = $value;

        return $this;
    }

    /**
     * Set the request token.
     */
    public function withToken(string $token, string $type = 'Bearer'): self
    {
        return $this->withHeader('Authorization', $type.' '.$token);
    }

    /**
     * Set the request accept header to application/json.
     *
     * @return $this
     */
    public function acceptJson(): self
    {
        return $this->withHeader('Accept', 'application/json');
    }

    /**
     * Set the request content type header to application/json.
     *
     * @return $this
     */
    public function asJson(): self
    {
        return $this->withHeader('Content-Type', 'application/json');
    }

    /**
     * Send the request.
     */
    public function send(): ResponseInterface
    {
        $request = $this->createRequest($this->method, $this->path, $this->options);

        // Apply interceptors
        foreach ($this->interceptors as $interceptor) {
            if (is_callable($interceptor)) {
                $request = $interceptor($request);
            } else {
                $request = $interceptor->apply($request);
            }
        }

        return $this->createResponse($request, $this->client->sendRequest($request));
    }

    /**
     * Create a new request instance.
     *
     * @param  array<string, mixed>  $options
     */
    public function createRequest(string $method, string $path, array $options = []): PsrRequestInterface
    {
        // Normalize path
        $path = '/'.trim($path, '/');

        // Query
        if (isset($options['query']) && is_array($options['query']) && ! empty($options['query'])) {
            $path .= '?'.http_build_query($options['query']);
        }

        // Set body
        if (isset($options['body'])) {
            $body = $options['body'];

            // Convert array to json
            if (is_array($body)) {
                $body = json_encode($body);
            }
        }

        // Set headers
        $headers = $options['headers'] ?? [];

        return new Request($method, $path, $headers, $body ?? null);
    }

    /**
     * Create a new response instance.
     */
    public function createResponse(
        PsrRequestInterface $request,
        PsrResponseInterface $baseResponse,
    ): ResponseInterface {
        $response = new Response($request, $baseResponse);

        return $response;
    }
}
