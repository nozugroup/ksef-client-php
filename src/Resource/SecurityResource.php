<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Resource;

use Nozu\KsefClient\Http\KsefRequest;
use Nozu\KsefClient\KsefResponse;
use Nozu\KsefClient\Transport\KsefHttpClient;

final class SecurityResource
{
    public function __construct(private readonly KsefHttpClient $http)
    {
    }

    public function publicKeyCertificates(): KsefResponse
    {
        return $this->http->send(KsefRequest::get('/security/public-key-certificates'));
    }
}
