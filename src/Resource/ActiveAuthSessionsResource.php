<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Resource;

use Nozu\KsefClient\Http\Headers;
use Nozu\KsefClient\Http\KsefRequest;
use Nozu\KsefClient\Http\PathParameters;
use Nozu\KsefClient\Http\QueryParameters;
use Nozu\KsefClient\KsefResponse;
use Nozu\KsefClient\Transport\KsefHttpClient;

final class ActiveAuthSessionsResource
{
    public function __construct(private readonly KsefHttpClient $http)
    {
    }

    public function list(?QueryParameters $query = null, ?Headers $headers = null): KsefResponse
    {
        return $this->http->send(KsefRequest::get('/auth/sessions', $query, $headers));
    }

    public function revokeCurrent(): KsefResponse
    {
        return $this->http->send(KsefRequest::delete('/auth/sessions/current'));
    }

    public function revoke(string $referenceNumber): KsefResponse
    {
        return $this->http->send(KsefRequest::delete('/auth/sessions/{referenceNumber}', pathParameters: PathParameters::empty()->with('referenceNumber', $referenceNumber)));
    }
}
