<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Qr;

final class QrCodeImage
{
    public function __construct(private readonly string $bytes)
    {
    }

    public function bytes(): string
    {
        return $this->bytes;
    }

    public function mimeType(): string
    {
        return 'image/png';
    }
}
