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

final class SessionsResource
{
    public function __construct(private readonly KsefHttpClient $http)
    {
    }

    public function list(QueryParameters $query, ?Headers $headers = null): KsefResponse
    {
        return $this->http->send(KsefRequest::get('/sessions', $query, $headers));
    }

    public function get(string $referenceNumber): KsefResponse
    {
        return $this->http->send(KsefRequest::get('/sessions/{referenceNumber}', pathParameters: $this->referenceNumber($referenceNumber)));
    }

    public function invoices(string $referenceNumber, ?QueryParameters $query = null, ?Headers $headers = null): KsefResponse
    {
        return $this->http->send(KsefRequest::get('/sessions/{referenceNumber}/invoices', $query, $headers, $this->referenceNumber($referenceNumber)));
    }

    public function invoice(string $referenceNumber, string $invoiceReferenceNumber): KsefResponse
    {
        return $this->http->send(KsefRequest::get('/sessions/{referenceNumber}/invoices/{invoiceReferenceNumber}', pathParameters: $this->referenceNumber($referenceNumber)->with('invoiceReferenceNumber', $invoiceReferenceNumber)));
    }

    public function failedInvoices(string $referenceNumber, ?QueryParameters $query = null, ?Headers $headers = null): KsefResponse
    {
        return $this->http->send(KsefRequest::get('/sessions/{referenceNumber}/invoices/failed', $query, $headers, $this->referenceNumber($referenceNumber)));
    }

    public function invoiceUpoByKsefNumber(string $referenceNumber, string $ksefNumber): KsefResponse
    {
        return $this->http->send(KsefRequest::get('/sessions/{referenceNumber}/invoices/ksef/{ksefNumber}/upo', pathParameters: $this->referenceNumber($referenceNumber)->with('ksefNumber', $ksefNumber)));
    }

    public function invoiceUpoByReferenceNumber(string $referenceNumber, string $invoiceReferenceNumber): KsefResponse
    {
        return $this->http->send(KsefRequest::get('/sessions/{referenceNumber}/invoices/{invoiceReferenceNumber}/upo', pathParameters: $this->referenceNumber($referenceNumber)->with('invoiceReferenceNumber', $invoiceReferenceNumber)));
    }

    public function upo(string $referenceNumber, string $upoReferenceNumber): KsefResponse
    {
        return $this->http->send(KsefRequest::get('/sessions/{referenceNumber}/upo/{upoReferenceNumber}', pathParameters: $this->referenceNumber($referenceNumber)->with('upoReferenceNumber', $upoReferenceNumber)));
    }

    public function openOnline(JsonBody $payload): KsefResponse
    {
        return $this->http->send(KsefRequest::post('/sessions/online', $payload));
    }

    public function sendOnlineInvoice(string $referenceNumber, JsonBody $payload): KsefResponse
    {
        return $this->http->send(KsefRequest::post('/sessions/online/{referenceNumber}/invoices', $payload, pathParameters: $this->referenceNumber($referenceNumber)));
    }

    public function closeOnline(string $referenceNumber): KsefResponse
    {
        return $this->http->send(KsefRequest::post('/sessions/online/{referenceNumber}/close', pathParameters: $this->referenceNumber($referenceNumber)));
    }

    public function openBatch(JsonBody $payload): KsefResponse
    {
        return $this->http->send(KsefRequest::post('/sessions/batch', $payload));
    }

    public function closeBatch(string $referenceNumber): KsefResponse
    {
        return $this->http->send(KsefRequest::post('/sessions/batch/{referenceNumber}/close', pathParameters: $this->referenceNumber($referenceNumber)));
    }

    private function referenceNumber(string $referenceNumber): PathParameters
    {
        return PathParameters::empty()->with('referenceNumber', $referenceNumber);
    }
}
