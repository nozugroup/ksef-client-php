<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Qr;

final class InvoiceHash
{
    private function __construct(private readonly Base64Url $value)
    {
    }

    public static function fromBase64Url(string $value): self
    {
        return new self(Base64Url::fromString($value));
    }

    public static function fromInvoiceXml(string $xml): self
    {
        return new self(Base64Url::encode(hash('sha256', $xml, true)));
    }

    public function value(): string
    {
        return $this->value->value();
    }
}
