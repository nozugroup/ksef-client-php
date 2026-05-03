<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Contract;

interface RequestBody
{
    public function contentType(): string;

    public function isJson(): bool;

    public function contents(): mixed;
}
