<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Qr;

final class SellerNip
{
    public function __construct(private readonly string $value)
    {
    }

    public function value(): string
    {
        return $this->value;
    }
}
