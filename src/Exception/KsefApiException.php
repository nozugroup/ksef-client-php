<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Exception;

use Nozu\KsefClient\KsefResponse;
use JsonException;
use RuntimeException;

final class KsefApiException extends RuntimeException
{
    public function __construct(
        private readonly KsefResponse $response,
        ?string $message = null,
    ) {
        parent::__construct($message ?? self::messageFromResponse($response), $response->statusCode());
    }

    public function response(): KsefResponse
    {
        return $this->response;
    }

    public function problem(): ?object
    {
        try {
            return $this->response->json()?->object();
        } catch (JsonException) {
            return null;
        }
    }

    private static function messageFromResponse(KsefResponse $response): string
    {
        try {
            $json = $response->json()?->object();
        } catch (JsonException) {
            $json = null;
        }

        if (is_object($json)) {
            foreach (['title', 'detail', 'message'] as $key) {
                if (isset($json->{$key}) && is_string($json->{$key})) {
                    return sprintf('KSeF API error %d: %s', $response->statusCode(), $json->{$key});
                }
            }
        }

        return sprintf('KSeF API error %d', $response->statusCode());
    }
}
