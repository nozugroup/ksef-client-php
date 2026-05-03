<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Resource;

use Nozu\KsefClient\Http\KsefRequest;
use Nozu\KsefClient\Http\QueryParameters;
use Nozu\KsefClient\KsefResponse;
use Nozu\KsefClient\Transport\KsefHttpClient;

final class PeppolResource
{
    public function __construct(private readonly KsefHttpClient $http)
    {
    }

    public function query(?QueryParameters $query = null): KsefResponse
    {
        return $this->http->send(KsefRequest::get('/peppol/query', $query));
    }
}
