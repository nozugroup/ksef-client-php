<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Resource;

use Nozu\KsefClient\Http\JsonBody;
use Nozu\KsefClient\Http\KsefRequest;
use Nozu\KsefClient\Http\PathParameters;
use Nozu\KsefClient\Http\QueryParameters;
use Nozu\KsefClient\KsefResponse;
use Nozu\KsefClient\Transport\KsefHttpClient;

final class PermissionsResource
{
    public function __construct(private readonly KsefHttpClient $http)
    {
    }

    public function grantPerson(JsonBody $payload): KsefResponse
    {
        return $this->http->send(KsefRequest::post('/permissions/persons/grants', $payload));
    }

    public function grantEntity(JsonBody $payload): KsefResponse
    {
        return $this->http->send(KsefRequest::post('/permissions/entities/grants', $payload));
    }

    public function grantAuthorization(JsonBody $payload): KsefResponse
    {
        return $this->http->send(KsefRequest::post('/permissions/authorizations/grants', $payload));
    }

    public function grantIndirect(JsonBody $payload): KsefResponse
    {
        return $this->http->send(KsefRequest::post('/permissions/indirect/grants', $payload));
    }

    public function grantSubunit(JsonBody $payload): KsefResponse
    {
        return $this->http->send(KsefRequest::post('/permissions/subunits/grants', $payload));
    }

    public function grantEuEntityAdministration(JsonBody $payload): KsefResponse
    {
        return $this->http->send(KsefRequest::post('/permissions/eu-entities/administration/grants', $payload));
    }

    public function grantEuEntity(JsonBody $payload): KsefResponse
    {
        return $this->http->send(KsefRequest::post('/permissions/eu-entities/grants', $payload));
    }

    public function revokeCommon(string $permissionId): KsefResponse
    {
        return $this->http->send(KsefRequest::delete('/permissions/common/grants/{permissionId}', pathParameters: $this->permissionId($permissionId)));
    }

    public function revokeAuthorization(string $permissionId): KsefResponse
    {
        return $this->http->send(KsefRequest::delete('/permissions/authorizations/grants/{permissionId}', pathParameters: $this->permissionId($permissionId)));
    }

    public function operationStatus(string $referenceNumber): KsefResponse
    {
        return $this->http->send(KsefRequest::get('/permissions/operations/{referenceNumber}', pathParameters: PathParameters::empty()->with('referenceNumber', $referenceNumber)));
    }

    public function attachmentStatus(): KsefResponse
    {
        return $this->http->send(KsefRequest::get('/permissions/attachments/status'));
    }

    public function queryPersonalGrants(JsonBody $payload, ?QueryParameters $query = null): KsefResponse
    {
        return $this->http->send(KsefRequest::post('/permissions/query/personal/grants', $payload, $query));
    }

    public function queryPersonGrants(JsonBody $payload, ?QueryParameters $query = null): KsefResponse
    {
        return $this->http->send(KsefRequest::post('/permissions/query/persons/grants', $payload, $query));
    }

    public function querySubunitGrants(JsonBody $payload, ?QueryParameters $query = null): KsefResponse
    {
        return $this->http->send(KsefRequest::post('/permissions/query/subunits/grants', $payload, $query));
    }

    public function queryEntityGrants(JsonBody $payload, ?QueryParameters $query = null): KsefResponse
    {
        return $this->http->send(KsefRequest::post('/permissions/query/entities/grants', $payload, $query));
    }

    public function queryEntityRoles(?QueryParameters $query = null): KsefResponse
    {
        return $this->http->send(KsefRequest::get('/permissions/query/entities/roles', $query));
    }

    public function querySubordinateEntityRoles(JsonBody $payload, ?QueryParameters $query = null): KsefResponse
    {
        return $this->http->send(KsefRequest::post('/permissions/query/subordinate-entities/roles', $payload, $query));
    }

    public function queryAuthorizationGrants(JsonBody $payload, ?QueryParameters $query = null): KsefResponse
    {
        return $this->http->send(KsefRequest::post('/permissions/query/authorizations/grants', $payload, $query));
    }

    public function queryEuEntityGrants(JsonBody $payload, ?QueryParameters $query = null): KsefResponse
    {
        return $this->http->send(KsefRequest::post('/permissions/query/eu-entities/grants', $payload, $query));
    }

    private function permissionId(string $permissionId): PathParameters
    {
        return PathParameters::empty()->with('permissionId', $permissionId);
    }
}
