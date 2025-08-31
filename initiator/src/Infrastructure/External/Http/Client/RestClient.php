<?php

declare(strict_types=1);

namespace Src\Infrastructure\External\Http\Client;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Log\Context\Repository as ContextRepository;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;
use Src\Infrastructure\External\Http\Enum\HttpVerb;
use Src\Infrastructure\External\Http\Exception\HeaderNormalizationException;
use Src\Infrastructure\External\Http\Exception\UrlNormalizationException;
use Throwable;

abstract class RestClient
{
    public string $baseUrl {
        get => $this->baseUrl;
    }
    public array $headers {
        get => $this->headers;
    }

    public function __construct(
        string $baseUrl,
        array $headers,
        private PendingRequest $client,
        private ConfigRepository $config,
        private ContextRepository $context,
        private Logger $logger,
    )
    {
        $this->baseUrl = $this->normalizeUrl($baseUrl);
        $this->headers = $this->normalizeHeaders($headers);
        $this->client = $client->baseUrl($this->baseUrl)->withHeaders($this->headers);
    }

    protected function normalizeUrl(string $url): string
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new UrlNormalizationException('Invalid URL format');
        }
        return str_ends_with($url, '/') ? $url : sprintf('%s/', $url);
    }

    protected function normalizeHeaders(array $headers): array
    {
        try {
            $traceHeader = $this->config->string('app.trace_header');
            $traceKey = $this->config->string('app.trace_key');
            $headers[$traceHeader] = $this->context->get($traceKey);

            $normalizedHeaders = array_change_key_case($headers);
            return array_map(fn($value) => (string) $value, $normalizedHeaders);
        } catch (Throwable $throwable) {
            throw new HeaderNormalizationException($throwable->getMessage(), $throwable->getCode(), $throwable);
        }
    }

    public function withHeaders(array $headers): void
    {
        $this->headers = $this->normalizeHeaders($headers);
        $this->client->withHeaders($this->headers);
    }

    protected function request(
        HttpVerb $verb,
        string $uri,
        array $query = [],
        array $body = [],
        array $headers = []
    ): Response
    {
        $this->logger->info('Request', [
            'verb' => $verb->value,
            'base_url' => $this->baseUrl,
            'uri' => $uri,
            'headers' => $this->headers,
            'query' => $query,
            'body' => $body
        ]);

        if ($headers) {
            $this->withHeaders($headers);
        }

        /** @var Response $response */
        $response = $this->client->{$verb->value}($uri, $query, $body);

        $this->logger->info('Response', [
            'status' => $response->status(),
            'headers' => $response->headers(),
            'body' => $response->body()
        ]);

        return $response;
    }

    public function get(string $uri, array $query = [], array $headers = []): Response
    {
        return $this->request(HttpVerb::GET, $uri, $query, $headers);
    }

    public function post(string $uri, array $query = [], array $body = [], array $headers = []): Response
    {
        return $this->request(HttpVerb::POST, $uri, $query, $body, $headers);
    }

    public function put(string $uri, array $query = [], array $body = [], array $headers = []): Response
    {
        return $this->request(HttpVerb::PUT, $uri, $query, $body, $headers);
    }

    public function patch(string $uri, array $query = [], array $body = [], array $headers = []): Response
    {
        return $this->request(HttpVerb::PATCH, $uri, $query, $body, $headers);
    }

    public function delete(string $uri, array $query = [], array $headers = []): Response
    {
        return $this->request(HttpVerb::DELETE, $uri, $query, $headers);
    }

    public function pool(callable $callback): array
    {
        return $this->client->pool($callback);
    }
}
