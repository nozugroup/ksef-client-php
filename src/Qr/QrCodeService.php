<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Qr;

final class QrCodeService
{
    public function __construct(
        private readonly VerificationLinkBuilder $links,
        private readonly QrCodeGenerator $generator = new QrCodeGenerator(),
        private readonly CertificateUrlSigner $signer = new CertificateUrlSigner(),
    ) {
    }

    public static function test(): self
    {
        return new self(new VerificationLinkBuilder(QrEnvironment::test()));
    }

    public static function demo(): self
    {
        return new self(new VerificationLinkBuilder(QrEnvironment::demo()));
    }

    public static function production(): self
    {
        return new self(new VerificationLinkBuilder(QrEnvironment::production()));
    }

    public function invoiceLink(SellerNip $sellerNip, IssueDate $issueDate, InvoiceHash $invoiceHash): VerificationLink
    {
        return $this->links->invoice($sellerNip, $issueDate, $invoiceHash);
    }

    public function invoiceQr(
        SellerNip $sellerNip,
        IssueDate $issueDate,
        InvoiceHash $invoiceHash,
        ?QrLabel $label = null,
        ?QrCodeOptions $options = null,
    ): QrCodeImage {
        return $this->generator->png($this->invoiceLink($sellerNip, $issueDate, $invoiceHash), $label, $options);
    }

    public function certificateLink(
        ContextIdentifier $contextIdentifier,
        SellerNip $sellerNip,
        CertificateSerialNumber $certificateSerialNumber,
        InvoiceHash $invoiceHash,
        OfflineCertificatePrivateKey $privateKey,
    ): VerificationLink {
        $payload = $this->links->unsignedCertificatePayload($contextIdentifier, $sellerNip, $certificateSerialNumber, $invoiceHash);
        $signature = $this->signer->sign($payload, $privateKey);

        return $this->links->certificate($contextIdentifier, $sellerNip, $certificateSerialNumber, $invoiceHash, $signature);
    }

    public function certificateQr(
        ContextIdentifier $contextIdentifier,
        SellerNip $sellerNip,
        CertificateSerialNumber $certificateSerialNumber,
        InvoiceHash $invoiceHash,
        OfflineCertificatePrivateKey $privateKey,
        ?QrCodeOptions $options = null,
    ): QrCodeImage {
        return $this->generator->png(
            $this->certificateLink($contextIdentifier, $sellerNip, $certificateSerialNumber, $invoiceHash, $privateKey),
            QrLabel::certificate(),
            $options,
        );
    }
}
