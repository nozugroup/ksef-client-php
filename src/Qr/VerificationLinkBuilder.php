<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Qr;

final class VerificationLinkBuilder
{
    public function __construct(private readonly QrEnvironment $environment)
    {
    }

    public function invoice(
        SellerNip $sellerNip,
        IssueDate $issueDate,
        InvoiceHash $invoiceHash,
    ): VerificationLink {
        return new VerificationLink(sprintf(
            '%s/invoice/%s/%s/%s',
            $this->environment->baseUrl(),
            rawurlencode($sellerNip->value()),
            rawurlencode($issueDate->value()),
            rawurlencode($invoiceHash->value()),
        ));
    }

    public function unsignedCertificatePayload(
        ContextIdentifier $contextIdentifier,
        SellerNip $sellerNip,
        CertificateSerialNumber $certificateSerialNumber,
        InvoiceHash $invoiceHash,
    ): CertificateSignaturePayload {
        return new CertificateSignaturePayload(sprintf(
            '%s/certificate/%s/%s/%s/%s/%s',
            $this->environment->host(),
            rawurlencode($contextIdentifier->type()),
            rawurlencode($contextIdentifier->value()),
            rawurlencode($sellerNip->value()),
            rawurlencode($certificateSerialNumber->value()),
            rawurlencode($invoiceHash->value()),
        ));
    }

    public function certificate(
        ContextIdentifier $contextIdentifier,
        SellerNip $sellerNip,
        CertificateSerialNumber $certificateSerialNumber,
        InvoiceHash $invoiceHash,
        Signature $signature,
    ): VerificationLink {
        return new VerificationLink(sprintf(
            '%s/certificate/%s/%s/%s/%s/%s/%s',
            $this->environment->baseUrl(),
            rawurlencode($contextIdentifier->type()),
            rawurlencode($contextIdentifier->value()),
            rawurlencode($sellerNip->value()),
            rawurlencode($certificateSerialNumber->value()),
            rawurlencode($invoiceHash->value()),
            rawurlencode($signature->value()),
        ));
    }
}
