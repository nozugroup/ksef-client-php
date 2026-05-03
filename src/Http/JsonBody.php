<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Http;

use JsonSerializable;
use Nozu\KsefClient\Contract\RequestBody;

final class JsonBody implements RequestBody
{
    private function __construct(private readonly object $payload)
    {
    }

    public static function fromObject(object $payload): self
    {
        return new self($payload);
    }

    public function contentType(): string
    {
        return 'application/json';
    }

    public function isJson(): bool
    {
        return true;
    }

    public function contents(): object
    {
        return $this->payload;
    }
}
