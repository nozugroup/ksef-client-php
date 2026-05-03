<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Resource;

use Nozu\KsefClient\Http\JsonBody;
use Nozu\KsefClient\Http\KsefRequest;
use Nozu\KsefClient\Http\PathParameters;
use Nozu\KsefClient\Http\QueryParameters;
use Nozu\KsefClient\KsefResponse;
use Nozu\KsefClient\Transport\KsefHttpClient;

final class CertificatesResource
{
    public function __construct(private readonly KsefHttpClient $http)
    {
    }

    public function limits(): KsefResponse
    {
        return $this->http->send(KsefRequest::get('/certificates/limits'));
    }

    public function enrollmentData(): KsefResponse
    {
        return $this->http->send(KsefRequest::get('/certificates/enrollments/data'));
    }

    public function enroll(JsonBody $payload): KsefResponse
    {
        return $this->http->send(KsefRequest::post('/certificates/enrollments', $payload));
    }

    public function enrollmentStatus(string $referenceNumber): KsefResponse
    {
        return $this->http->send(KsefRequest::get('/certificates/enrollments/{referenceNumber}', pathParameters: PathParameters::empty()->with('referenceNumber', $referenceNumber)));
    }

    public function retrieve(JsonBody $payload): KsefResponse
    {
        return $this->http->send(KsefRequest::post('/certificates/retrieve', $payload));
    }

    public function revoke(string $certificateSerialNumber, JsonBody $payload): KsefResponse
    {
        return $this->http->send(KsefRequest::post('/certificates/{certificateSerialNumber}/revoke', $payload, pathParameters: PathParameters::empty()->with('certificateSerialNumber', $certificateSerialNumber)));
    }

    public function query(JsonBody $payload, ?QueryParameters $query = null): KsefResponse
    {
        return $this->http->send(KsefRequest::post('/certificates/query', $payload, $query));
    }
}
