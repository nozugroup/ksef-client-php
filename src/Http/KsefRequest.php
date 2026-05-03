<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Http;

use Nozu\KsefClient\Contract\RequestBody;

final class KsefRequest
{
    private function __construct(
        private readonly string $method,
        private readonly string $path,
        private readonly QueryParameters $query,
        private readonly Headers $headers,
        private readonly PathParameters $pathParameters,
        private readonly ?RequestBody $body,
    ) {
    }

    public static function get(string $path, ?QueryParameters $query = null, ?Headers $headers = null, ?PathParameters $pathParameters = null): self
    {
        return new self('GET', $path, $query ?? QueryParameters::empty(), $headers ?? Headers::empty(), $pathParameters ?? PathParameters::empty(), null);
    }

    public static function post(string $path, ?RequestBody $body = null, ?QueryParameters $query = null, ?Headers $headers = null, ?PathParameters $pathParameters = null): self
    {
        return new self('POST', $path, $query ?? QueryParameters::empty(), $headers ?? Headers::empty(), $pathParameters ?? PathParameters::empty(), $body);
    }

    public static function delete(string $path, ?QueryParameters $query = null, ?Headers $headers = null, ?PathParameters $pathParameters = null): self
    {
        return new self('DELETE', $path, $query ?? QueryParameters::empty(), $headers ?? Headers::empty(), $pathParameters ?? PathParameters::empty(), null);
    }

    public function method(): string
    {
        return $this->method;
    }

    public function path(): string
    {
        return $this->pathParameters->apply($this->path);
    }

    public function query(): QueryParameters
    {
        return $this->query;
    }

    public function headers(): Headers
    {
        return $this->headers;
    }

    public function body(): ?RequestBody
    {
        return $this->body;
    }
}
