<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Http;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int, string>
 */
final class StringList implements IteratorAggregate
{
    /**
     * @var list<string>
     */
    private readonly array $values;

    public function __construct(string $first, string ...$rest)
    {
        $this->values = [$first, ...$rest];
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->values);
    }
}
