<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Http;

final class QueryParameters
{
    /**
     * @param array<string, list<string>> $values
     */
    private function __construct(private readonly array $values)
    {
    }

    public static function empty(): self
    {
        return new self([]);
    }

    public function withString(string $name, string $value): self
    {
        return $this->withValues($name, new StringList($value));
    }

    public function withInt(string $name, int $value): self
    {
        return $this->withString($name, (string) $value);
    }

    public function withBool(string $name, bool $value): self
    {
        return $this->withString($name, $value ? 'true' : 'false');
    }

    public function withValues(string $name, StringList $values): self
    {
        $query = $this->values;
        $query[$name] = iterator_to_array($values);

        return new self($query);
    }

    public function toQueryString(): string
    {
        $parts = [];

        foreach ($this->values as $name => $values) {
            foreach ($values as $value) {
                $parts[] = rawurlencode($name) . '=' . rawurlencode($value);
            }
        }

        return implode('&', $parts);
    }
}
