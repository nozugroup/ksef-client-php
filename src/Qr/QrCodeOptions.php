<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Qr;

final class QrCodeOptions
{
    public function __construct(
        public readonly int $size = 300,
        public readonly int $margin = 10,
        public readonly int $labelFontSize = 16,
    ) {
    }
}
