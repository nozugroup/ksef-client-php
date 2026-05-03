<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Resource;

use Nozu\KsefClient\Http\JsonBody;
use Nozu\KsefClient\Http\KsefRequest;
use Nozu\KsefClient\Http\PathParameters;
use Nozu\KsefClient\Http\QueryParameters;
use Nozu\KsefClient\KsefResponse;
use Nozu\KsefClient\Transport\KsefHttpClient;

final class InvoicesResource
{
    public function __construct(private readonly KsefHttpClient $http)
    {
    }

    public function getByKsefNumber(string $ksefNumber): KsefResponse
    {
        return $this->http->send(KsefRequest::get('/invoices/ksef/{ksefNumber}', pathParameters: PathParameters::empty()->with('ksefNumber', $ksefNumber)));
    }

    public function queryMetadata(JsonBody $payload, ?QueryParameters $query = null): KsefResponse
    {
        return $this->http->send(KsefRequest::post('/invoices/query/metadata', $payload, $query));
    }

    public function export(JsonBody $payload): KsefResponse
    {
        return $this->http->send(KsefRequest::post('/invoices/exports', $payload));
    }

    public function exportStatus(string $referenceNumber): KsefResponse
    {
        return $this->http->send(KsefRequest::get('/invoices/exports/{referenceNumber}', pathParameters: PathParameters::empty()->with('referenceNumber', $referenceNumber)));
    }
}
