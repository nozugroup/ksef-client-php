<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Qr;

use phpseclib3\Crypt\EC\PrivateKey as EcPrivateKey;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\RSA\PrivateKey as RsaPrivateKey;
use RuntimeException;

final class CertificateUrlSigner
{
    public function sign(CertificateSignaturePayload $payload, OfflineCertificatePrivateKey $privateKey): Signature
    {
        $key = PublicKeyLoader::loadPrivateKey($privateKey->pem(), $privateKey->password() ?? false);

        if ($key instanceof RsaPrivateKey) {
            return Signature::fromBytes(
                $key
                    ->withPadding(RSA::SIGNATURE_PSS)
                    ->withHash('sha256')
                    ->withMGFHash('sha256')
                    ->withSaltLength(32)
                    ->sign($payload->value())
            );
        }

        if ($key instanceof EcPrivateKey) {
            return Signature::fromBytes(
                $key
                    ->withHash('sha256')
                    ->withSignatureFormat('IEEE')
                    ->sign($payload->value())
            );
        }

        throw new RuntimeException('Unsupported private key type for KSeF QR certificate URL signing.');
    }
}
