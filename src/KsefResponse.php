<?php

declare(strict_types=1);

namespace Nozu\KsefClient;

use JsonException;
use Nozu\KsefClient\Http\JsonData;

final class KsefResponse
{
    /**
     */
    public function __construct(
        private readonly int $statusCode,
        private readonly ResponseHeaders $headers,
        private readonly string $body,
    ) {
    }

    public function statusCode(): int
    {
        return $this->statusCode;
    }

    public function headers(): ResponseHeaders
    {
        return $this->headers;
    }

    public function header(string $name): ?string
    {
        return $this->headers->first($name);
    }

    public function body(): string
    {
        return $this->body;
    }

    /**
     * @throws JsonException
     */
    public function json(): ?JsonData
    {
        return JsonData::fromString($this->body);
    }
}
