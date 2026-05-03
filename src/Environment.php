<?php

declare(strict_types=1);

namespace Nozu\KsefClient;

final class Environment
{
    public const PRODUCTION = 'https://api.ksef.mf.gov.pl/v2';
    public const TEST = 'https://api-test.ksef.mf.gov.pl/v2';
    public const DEMO = 'https://api-demo.ksef.mf.gov.pl/v2';

    private function __construct()
    {
    }
}
