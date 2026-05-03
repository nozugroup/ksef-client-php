<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Resource;

use Nozu\KsefClient\Http\Headers;
use Nozu\KsefClient\Http\JsonBody;
use Nozu\KsefClient\Http\KsefRequest;
use Nozu\KsefClient\Http\PathParameters;
use Nozu\KsefClient\Http\QueryParameters;
use Nozu\KsefClient\KsefResponse;
use Nozu\KsefClient\Transport\KsefHttpClient;

final class TokensResource
{
    public function __construct(private readonly KsefHttpClient $http)
    {
    }

    public function generate(JsonBody $payload): KsefResponse
    {
        return $this->http->send(KsefRequest::post('/tokens', $payload));
    }

    public function list(?QueryParameters $query = null, ?Headers $headers = null): KsefResponse
    {
        return $this->http->send(KsefRequest::get('/tokens', $query, $headers));
    }

    public function get(string $referenceNumber): KsefResponse
    {
        return $this->http->send(KsefRequest::get('/tokens/{referenceNumber}', pathParameters: PathParameters::empty()->with('referenceNumber', $referenceNumber)));
    }

    public function revoke(string $referenceNumber): KsefResponse
    {
        return $this->http->send(KsefRequest::delete('/tokens/{referenceNumber}', pathParameters: PathParameters::empty()->with('referenceNumber', $referenceNumber)));
    }
}
