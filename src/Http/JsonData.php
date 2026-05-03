<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Http;

use JsonException;
use stdClass;

final class JsonData
{
    private function __construct(private readonly mixed $value)
    {
    }

    /**
     * @throws JsonException
     */
    public static function fromString(string $json): ?self
    {
        if ($json === '') {
            return null;
        }

        return new self(json_decode($json, false, flags: JSON_THROW_ON_ERROR));
    }

    public function object(): ?stdClass
    {
        return $this->value instanceof stdClass ? $this->value : null;
    }

    /**
     * @return iterable<int, mixed>
     */
    public function items(): iterable
    {
        if (!is_array($this->value)) {
            return;
        }

        foreach ($this->value as $item) {
            yield $item;
        }
    }
}
