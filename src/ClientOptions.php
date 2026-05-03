<?php

declare(strict_types=1);

namespace Nozu\KsefClient;

use GuzzleHttp\ClientInterface;

final class ClientOptions
{
    public function __construct(
        public readonly string $baseUri = Environment::PRODUCTION,
        public readonly ?string $accessToken = null,
        public readonly ?ClientInterface $httpClient = null,
        public readonly bool $problemDetails = true,
    ) {
    }
}
