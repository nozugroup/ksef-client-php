<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Qr;

final class OfflineCertificatePrivateKey
{
    private function __construct(
        private readonly string $pem,
        private readonly ?string $password,
    ) {
    }

    public static function fromPem(string $pem, ?string $password = null): self
    {
        return new self($pem, $password);
    }

    public function pem(): string
    {
        return $this->pem;
    }

    public function password(): ?string
    {
        return $this->password;
    }
}
