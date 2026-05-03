<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Resource;

use Nozu\KsefClient\Http\JsonBody;
use Nozu\KsefClient\Http\KsefRequest;
use Nozu\KsefClient\Http\PathParameters;
use Nozu\KsefClient\Http\QueryParameters;
use Nozu\KsefClient\Http\XmlBody;
use Nozu\KsefClient\KsefResponse;
use Nozu\KsefClient\Transport\KsefHttpClient;

final class AuthResource
{
    public function __construct(private readonly KsefHttpClient $http)
    {
    }

    public function createChallenge(): KsefResponse
    {
        return $this->http->send(KsefRequest::post('/auth/challenge'));
    }

    public function authenticateWithXadesSignature(XmlBody $signature, ?QueryParameters $query = null): KsefResponse
    {
        return $this->http->send(KsefRequest::post('/auth/xades-signature', $signature, $query));
    }

    public function authenticateWithKsefToken(JsonBody $payload): KsefResponse
    {
        return $this->http->send(KsefRequest::post('/auth/ksef-token', $payload));
    }

    public function status(string $referenceNumber): KsefResponse
    {
        return $this->http->send(KsefRequest::get('/auth/{referenceNumber}', pathParameters: PathParameters::empty()->with('referenceNumber', $referenceNumber)));
    }

    public function redeemToken(): KsefResponse
    {
        return $this->http->send(KsefRequest::post('/auth/token/redeem'));
    }

    public function refreshToken(): KsefResponse
    {
        return $this->http->send(KsefRequest::post('/auth/token/refresh'));
    }
}
