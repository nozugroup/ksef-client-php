<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Qr;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\Font\Font;
use Endroid\QrCode\Writer\PngWriter;
use ReflectionClass;

final class QrCodeGenerator
{
    public function png(VerificationLink $link, ?QrLabel $label = null, ?QrCodeOptions $options = null): QrCodeImage
    {
        $options ??= new QrCodeOptions();

        $result = (new Builder())->build(
            writer: new PngWriter(),
            data: $link->url(),
            errorCorrectionLevel: ErrorCorrectionLevel::Low,
            size: $options->size,
            margin: $options->margin,
            labelText: $label?->text(),
            labelFont: new Font($this->defaultFontPath(), $options->labelFontSize),
        );

        return new QrCodeImage($result->getString());
    }

    private function defaultFontPath(): string
    {
        return dirname((string) (new ReflectionClass(Font::class))->getFileName(), 4) . '/assets/open_sans.ttf';
    }
}
