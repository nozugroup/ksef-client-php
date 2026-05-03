<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Http;

final class PathParameters
{
    /**
     * @param array<string, string> $values
     */
    private function __construct(private readonly array $values)
    {
    }

    public static function empty(): self
    {
        return new self([]);
    }

    public function with(string $name, string $value): self
    {
        $values = $this->values;
        $values[$name] = $value;

        return new self($values);
    }

    public function apply(string $path): string
    {
        foreach ($this->values as $name => $value) {
            $path = str_replace('{' . $name . '}', rawurlencode($value), $path);
        }

        return $path;
    }
}
