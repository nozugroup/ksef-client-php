<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Qr;

final class Base64Url
{
    private function __construct(private readonly string $value)
    {
    }

    public static function encode(string $bytes): self
    {
        return new self(rtrim(strtr(base64_encode($bytes), '+/', '-_'), '='));
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }
}
