<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Transport;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Nozu\KsefClient\Environment;
use Nozu\KsefClient\Exception\KsefApiException;
use Nozu\KsefClient\Http\KsefRequest;
use Nozu\KsefClient\KsefResponse;
use Nozu\KsefClient\ResponseHeaders;
use Psr\Http\Message\ResponseInterface;

final class KsefHttpClient
{
    private ClientInterface $httpClient;
    private ?string $accessToken = null;

    public function __construct(
        private readonly string $baseUri = Environment::PRODUCTION,
        ?ClientInterface $httpClient = null,
        private readonly bool $problemDetails = true,
    ) {
        $this->httpClient = $httpClient ?? new Client([
            'base_uri' => rtrim($this->baseUri, '/') . '/',
            'http_errors' => false,
            'timeout' => 30,
        ]);
    }

    public function withAccessToken(?string $accessToken): self
    {
        $clone = clone $this;
        $clone->accessToken = $accessToken;

        return $clone;
    }

    /**
     * @throws KsefApiException
     */
    public function send(KsefRequest $request): KsefResponse
    {
        $options = [
            'headers' => $this->headers($request),
        ];

        $query = $request->query()->toQueryString();
        if ($query !== '') {
            $options['query'] = $query;
        }

        $body = $request->body();
        if ($body !== null) {
            if ($body->isJson()) {
                $options['json'] = $body->contents();
            } else {
                $options['body'] = $body->contents();
            }
        }

        try {
            $response = $this->httpClient->request($request->method(), ltrim($request->path(), '/'), $options);
        } catch (RequestException $exception) {
            if ($exception->hasResponse()) {
                throw new KsefApiException($this->toKsefResponse($exception->getResponse()), $exception->getMessage());
            }

            throw new KsefApiException(new KsefResponse(0, ResponseHeaders::empty(), ''), $exception->getMessage());
        } catch (GuzzleException $exception) {
            throw new KsefApiException(new KsefResponse(0, ResponseHeaders::empty(), ''), $exception->getMessage());
        }

        $ksefResponse = $this->toKsefResponse($response);

        if ($ksefResponse->statusCode() >= 400) {
            throw new KsefApiException($ksefResponse);
        }

        return $ksefResponse;
    }

    /**
     * @return array<string, string>
     */
    private function headers(KsefRequest $request): array
    {
        $headers = [
            'Accept' => 'application/json, application/xml, text/xml, */*',
        ];

        if ($this->problemDetails) {
            $headers['X-Error-Format'] = 'problem-details';
        }

        $body = $request->body();
        if ($body !== null) {
            $headers['Content-Type'] = $body->contentType();
        }

        if ($this->accessToken !== null) {
            $headers['Authorization'] = 'Bearer ' . $this->accessToken;
        }

        return array_replace($headers, iterator_to_array($request->headers()));
    }

    private function toKsefResponse(ResponseInterface $response): KsefResponse
    {
        return new KsefResponse(
            $response->getStatusCode(),
            ResponseHeaders::fromResponse($response),
            (string) $response->getBody(),
        );
    }
}
