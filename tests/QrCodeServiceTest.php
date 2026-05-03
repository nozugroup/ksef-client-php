<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Tests;

use Nozu\KsefClient\Qr\CertificateSerialNumber;
use Nozu\KsefClient\Qr\ContextIdentifier;
use Nozu\KsefClient\Qr\InvoiceHash;
use Nozu\KsefClient\Qr\IssueDate;
use Nozu\KsefClient\Qr\OfflineCertificatePrivateKey;
use Nozu\KsefClient\Qr\QrCodeOptions;
use Nozu\KsefClient\Qr\QrCodeService;
use Nozu\KsefClient\Qr\QrEnvironment;
use Nozu\KsefClient\Qr\QrLabel;
use Nozu\KsefClient\Qr\SellerNip;
use Nozu\KsefClient\Qr\Signature;
use Nozu\KsefClient\Qr\VerificationLinkBuilder;
use phpseclib3\Crypt\RSA;
use PHPUnit\Framework\TestCase;

final class QrCodeServiceTest extends TestCase
{
    public function testItBuildsInvoiceVerificationLinkFromOfficialExample(): void
    {
        $service = QrCodeService::test();

        $link = $service->invoiceLink(
            new SellerNip('1111111111'),
            IssueDate::fromKsefFormat('01-02-2026'),
            InvoiceHash::fromBase64Url('UtQp9Gpc51y-u3xApZjIjgkpZ01js-J8KflSPW8WzIE'),
        );

        self::assertSame(
            'https://qr-test.ksef.mf.gov.pl/invoice/1111111111/01-02-2026/UtQp9Gpc51y-u3xApZjIjgkpZ01js-J8KflSPW8WzIE',
            $link->url(),
        );
    }

    public function testItBuildsCertificateVerificationLinkFromOfficialExample(): void
    {
        $builder = new VerificationLinkBuilder(QrEnvironment::test());

        $link = $builder->certificate(
            ContextIdentifier::nip('1111111111'),
            new SellerNip('1111111111'),
            new CertificateSerialNumber('01F20A5D352AE590'),
            InvoiceHash::fromBase64Url('UtQp9Gpc51y-u3xApZjIjgkpZ01js-J8KflSPW8WzIE'),
            Signature::fromBase64Url('signature'),
        );

        self::assertSame(
            'https://qr-test.ksef.mf.gov.pl/certificate/Nip/1111111111/1111111111/01F20A5D352AE590/UtQp9Gpc51y-u3xApZjIjgkpZ01js-J8KflSPW8WzIE/signature',
            $link->url(),
        );
    }

    public function testItGeneratesPngQrCodeWithLabel(): void
    {
        $image = QrCodeService::test()->invoiceQr(
            new SellerNip('1111111111'),
            IssueDate::fromKsefFormat('01-02-2026'),
            InvoiceHash::fromBase64Url('UtQp9Gpc51y-u3xApZjIjgkpZ01js-J8KflSPW8WzIE'),
            QrLabel::offline(),
            new QrCodeOptions(size: 180, margin: 5, labelFontSize: 12),
        );

        self::assertSame('image/png', $image->mimeType());
        self::assertStringStartsWith("\x89PNG\r\n\x1A\n", $image->bytes());
    }

    public function testItSignsCertificateLinkWithRsaPss(): void
    {
        $privateKey = RSA::createKey(2048)->toString('PKCS8');

        $link = QrCodeService::test()->certificateLink(
            ContextIdentifier::nip('1111111111'),
            new SellerNip('1111111111'),
            new CertificateSerialNumber('01F20A5D352AE590'),
            InvoiceHash::fromBase64Url('UtQp9Gpc51y-u3xApZjIjgkpZ01js-J8KflSPW8WzIE'),
            OfflineCertificatePrivateKey::fromPem($privateKey),
        );

        self::assertMatchesRegularExpression(
            '#^https://qr-test\.ksef\.mf\.gov\.pl/certificate/Nip/1111111111/1111111111/01F20A5D352AE590/UtQp9Gpc51y-u3xApZjIjgkpZ01js-J8KflSPW8WzIE/[A-Za-z0-9_-]+$#',
            $link->url(),
        );
    }
}
