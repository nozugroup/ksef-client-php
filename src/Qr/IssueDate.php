<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Qr;

use DateTimeInterface;

final class IssueDate
{
    private function __construct(private readonly string $value)
    {
    }

    public static function fromDate(DateTimeInterface $date): self
    {
        return new self($date->format('d-m-Y'));
    }

    public static function fromKsefFormat(string $date): self
    {
        return new self($date);
    }

    public function value(): string
    {
        return $this->value;
    }
}
