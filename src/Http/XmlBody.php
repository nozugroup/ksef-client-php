<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Http;

use Nozu\KsefClient\Contract\RequestBody;

final class XmlBody implements RequestBody
{
    private function __construct(private readonly string $xml)
    {
    }

    public static function fromString(string $xml): self
    {
        return new self($xml);
    }

    public function contentType(): string
    {
        return 'application/xml';
    }

    public function isJson(): bool
    {
        return false;
    }

    public function contents(): string
    {
        return $this->xml;
    }
}
