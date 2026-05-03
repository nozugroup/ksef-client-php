<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Qr;

final class ContextIdentifier
{
    private const NIP = 'Nip';
    private const INTERNAL_ID = 'InternalId';
    private const NIP_VAT_UE = 'NipVatUe';
    private const PEPPOL_ID = 'PeppolId';

    private function __construct(
        private readonly string $type,
        private readonly string $value,
    ) {
    }

    public static function nip(string $value): self
    {
        return new self(self::NIP, $value);
    }

    public static function internalId(string $value): self
    {
        return new self(self::INTERNAL_ID, $value);
    }

    public static function nipVatUe(string $value): self
    {
        return new self(self::NIP_VAT_UE, $value);
    }

    public static function peppolId(string $value): self
    {
        return new self(self::PEPPOL_ID, $value);
    }

    public function type(): string
    {
        return $this->type;
    }

    public function value(): string
    {
        return $this->value;
    }
}
