<?php

declare(strict_types=1);

namespace Nozu\KsefClient;

use Nozu\KsefClient\Http\KsefRequest;
use Nozu\KsefClient\Resource\ActiveAuthSessionsResource;
use Nozu\KsefClient\Resource\AuthResource;
use Nozu\KsefClient\Resource\CertificatesResource;
use Nozu\KsefClient\Resource\InvoicesResource;
use Nozu\KsefClient\Resource\LimitsResource;
use Nozu\KsefClient\Resource\PeppolResource;
use Nozu\KsefClient\Resource\PermissionsResource;
use Nozu\KsefClient\Resource\SecurityResource;
use Nozu\KsefClient\Resource\SessionsResource;
use Nozu\KsefClient\Resource\TokensResource;
use Nozu\KsefClient\Transport\KsefHttpClient;

final class KsefClient
{
    private function __construct(private readonly KsefHttpClient $http)
    {
    }

    /**
     * @deprecated Use ClientOptions for custom construction.
     */
    public static function create(
        ?ClientOptions $options = null,
    ): self {
        $options ??= new ClientOptions();
        $http = new KsefHttpClient($options->baseUri, $options->httpClient, $options->problemDetails);

        return new self($http->withAccessToken($options->accessToken));
    }

    public static function production(?string $accessToken = null): self
    {
        return self::create(new ClientOptions(Environment::PRODUCTION, $accessToken));
    }

    public static function test(?string $accessToken = null): self
    {
        return self::create(new ClientOptions(Environment::TEST, $accessToken));
    }

    public static function demo(?string $accessToken = null): self
    {
        return self::create(new ClientOptions(Environment::DEMO, $accessToken));
    }

    public function withAccessToken(?string $accessToken): self
    {
        return new self($this->http->withAccessToken($accessToken));
    }

    public function send(KsefRequest $request): KsefResponse
    {
        return $this->http->send($request);
    }

    public function auth(): AuthResource
    {
        return new AuthResource($this->http);
    }

    public function activeAuthSessions(): ActiveAuthSessionsResource
    {
        return new ActiveAuthSessionsResource($this->http);
    }

    public function certificates(): CertificatesResource
    {
        return new CertificatesResource($this->http);
    }

    public function security(): SecurityResource
    {
        return new SecurityResource($this->http);
    }

    public function limits(): LimitsResource
    {
        return new LimitsResource($this->http);
    }

    public function permissions(): PermissionsResource
    {
        return new PermissionsResource($this->http);
    }

    public function invoices(): InvoicesResource
    {
        return new InvoicesResource($this->http);
    }

    public function sessions(): SessionsResource
    {
        return new SessionsResource($this->http);
    }

    public function tokens(): TokensResource
    {
        return new TokensResource($this->http);
    }

    public function peppol(): PeppolResource
    {
        return new PeppolResource($this->http);
    }
}
