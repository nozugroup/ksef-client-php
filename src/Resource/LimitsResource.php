<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Resource;

use Nozu\KsefClient\Http\KsefRequest;
use Nozu\KsefClient\KsefResponse;
use Nozu\KsefClient\Transport\KsefHttpClient;

final class LimitsResource
{
    public function __construct(private readonly KsefHttpClient $http)
    {
    }

    public function context(): KsefResponse
    {
        return $this->http->send(KsefRequest::get('/limits/context'));
    }

    public function subject(): KsefResponse
    {
        return $this->http->send(KsefRequest::get('/limits/subject'));
    }

    public function rateLimits(): KsefResponse
    {
        return $this->http->send(KsefRequest::get('/rate-limits'));
    }
}
