<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Qr;

final class VerificationLink
{
    public function __construct(private readonly string $url)
    {
    }

    public function url(): string
    {
        return $this->url;
    }
}
