<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Http;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<string, string>
 */
final class Headers implements IteratorAggregate
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

    public static function continuationToken(string $token): self
    {
        return self::empty()->with('x-continuation-token', $token);
    }

    public function with(string $name, string $value): self
    {
        $headers = $this->values;
        $headers[$name] = $value;

        return new self($headers);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->values);
    }
}
