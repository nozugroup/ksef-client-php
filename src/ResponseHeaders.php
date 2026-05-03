<?php

declare(strict_types=1);

namespace Nozu\KsefClient;

use Psr\Http\Message\ResponseInterface;

final class ResponseHeaders
{
    /**
     * @param array<string, list<string>> $headers
     */
    private function __construct(private readonly array $headers)
    {
    }

    public static function empty(): self
    {
        return new self([]);
    }

    public static function fromResponse(ResponseInterface $response): self
    {
        return new self($response->getHeaders());
    }

    public function first(string $name): ?string
    {
        foreach ($this->headers as $headerName => $values) {
            if (strcasecmp($headerName, $name) === 0) {
                return $values[0] ?? null;
            }
        }

        return null;
    }
}
