<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Qr;

final class QrEnvironment
{
    public const PRODUCTION = 'https://qr.ksef.mf.gov.pl';
    public const TEST = 'https://qr-test.ksef.mf.gov.pl';
    public const DEMO = 'https://qr-demo.ksef.mf.gov.pl';

    private function __construct(private readonly string $baseUrl)
    {
    }

    public static function production(): self
    {
        return new self(self::PRODUCTION);
    }

    public static function test(): self
    {
        return new self(self::TEST);
    }

    public static function demo(): self
    {
        return new self(self::DEMO);
    }

    public function baseUrl(): string
    {
        return rtrim($this->baseUrl, '/');
    }

    public function host(): string
    {
        return (string) parse_url($this->baseUrl(), PHP_URL_HOST);
    }
}
