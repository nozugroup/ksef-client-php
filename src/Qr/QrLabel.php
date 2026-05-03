<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Qr;

final class QrLabel
{
    private function __construct(private readonly string $text)
    {
    }

    public static function ksefNumber(string $number): self
    {
        return new self($number);
    }

    public static function offline(): self
    {
        return new self('OFFLINE');
    }

    public static function certificate(): self
    {
        return new self('CERTYFIKAT');
    }

    public function text(): string
    {
        return $this->text;
    }
}
