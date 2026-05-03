<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Qr;

final class Signature
{
    private function __construct(private readonly Base64Url $value)
    {
    }

    public static function fromBytes(string $bytes): self
    {
        return new self(Base64Url::encode($bytes));
    }

    public static function fromBase64Url(string $value): self
    {
        return new self(Base64Url::fromString($value));
    }

    public function value(): string
    {
        return $this->value->value();
    }
}
