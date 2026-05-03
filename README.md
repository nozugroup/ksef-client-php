# KSeF Client PHP

Thin, modular PHP wrapper for the KSeF 2.0 REST API.

The endpoint surface was mapped from the official OpenAPI document:

- `https://api.ksef.mf.gov.pl/docs/v2/index.html`
- `https://api.ksef.mf.gov.pl/docs/v2/openapi.json`

The production contract checked for this scaffold reports API build `2.4.0-pr-20260423.2` and base URL `https://api.ksef.mf.gov.pl/v2`.

## Install

```bash
composer install
```

## Shape

Endpoints are grouped into final resource classes:

- `$client->auth()`
- `$client->activeAuthSessions()`
- `$client->certificates()`
- `$client->security()`
- `$client->limits()`
- `$client->permissions()`
- `$client->invoices()`
- `$client->sessions()`
- `$client->tokens()`
- `$client->peppol()`

The public API avoids raw request arrays. JSON/XML bodies, query parameters, headers, path parameters, requests, response headers, and decoded JSON are represented by final classes.

## Basic Usage

```php
use Nozu\KsefClient\Http\JsonBody;
use Nozu\KsefClient\KsefClient;

$client = KsefClient::test();

$challenge = $client->auth()->createChallenge()->json()?->object();

$auth = $client->auth()->authenticateWithKsefToken(
    JsonBody::fromObject((object) [
        'challenge' => $challenge?->challenge,
        'contextIdentifier' => (object) [
            'type' => 'Nip',
            'value' => '1234567890',
        ],
        'encryptedToken' => '...',
    ])
)->json()?->object();

$authStatus = $client->auth()->status($auth->referenceNumber)->json()?->object();
```

After you redeem an access token, pass it into the client:

```php
use Nozu\KsefClient\Http\QueryParameters;

$client = KsefClient::test($accessToken);

$sessions = $client->sessions()->list(
    QueryParameters::empty()
        ->withString('sessionType', 'Online')
        ->withInt('pageSize', 10)
)->json()?->object();
```

For repeated OpenAPI query parameters, use `StringList`:

```php
use Nozu\KsefClient\Http\StringList;

$query = QueryParameters::empty()
    ->withString('sessionType', 'Online')
    ->withValues('statuses', new StringList('InProgress', 'Succeeded'));
```

For paginated endpoints with continuation tokens, use `Headers`:

```php
use Nozu\KsefClient\Http\Headers;

$tokens = $client->tokens()->list(
    QueryParameters::empty()->withInt('pageSize', 10),
    Headers::continuationToken('...')
);
```

For operations that return XML or binary-like payloads, use `body()`:

```php
$invoiceXml = $client->invoices()->getByKsefNumber('...')->body();
$upoXml = $client->sessions()->upo('...', '...')->body();
```

## Generic Request Escape Hatch

The client exposes typed resource methods for every endpoint in the current OpenAPI contract, but you can still call new or changed endpoints directly:

```php
use Nozu\KsefClient\Http\KsefRequest;

$response = $client->send(KsefRequest::get('/rate-limits'));
```

## Notes

This package does not implement cryptographic preparation of XAdES signatures, encryption of KSeF tokens, or invoice XML generation. Those are separate domain steps; this client is responsible for HTTP transport, authentication headers, endpoint paths, query/header handling, JSON/XML bodies, and response/error wrapping.

## QR Codes

KSeF QR codes are generated locally from invoice data. The package supports:

- KOD I: invoice verification/download URL and QR code.
- KOD II: offline certificate verification URL and QR code signed with the KSeF Offline certificate private key.

```php
use Nozu\KsefClient\Qr\InvoiceHash;
use Nozu\KsefClient\Qr\IssueDate;
use Nozu\KsefClient\Qr\QrCodeService;
use Nozu\KsefClient\Qr\QrLabel;
use Nozu\KsefClient\Qr\SellerNip;

$qr = QrCodeService::test();

$link = $qr->invoiceLink(
    new SellerNip('1111111111'),
    IssueDate::fromKsefFormat('01-02-2026'),
    InvoiceHash::fromBase64Url('UtQp9Gpc51y-u3xApZjIjgkpZ01js-J8KflSPW8WzIE')
);

$png = $qr->invoiceQr(
    new SellerNip('1111111111'),
    IssueDate::fromKsefFormat('01-02-2026'),
    InvoiceHash::fromInvoiceXml($invoiceXml),
    QrLabel::offline()
);
```

For KOD II:

```php
use Nozu\KsefClient\Qr\CertificateSerialNumber;
use Nozu\KsefClient\Qr\ContextIdentifier;
use Nozu\KsefClient\Qr\OfflineCertificatePrivateKey;

$certificateQr = $qr->certificateQr(
    ContextIdentifier::nip('1111111111'),
    new SellerNip('1111111111'),
    new CertificateSerialNumber('01F20A5D352AE590'),
    InvoiceHash::fromInvoiceXml($invoiceXml),
    OfflineCertificatePrivateKey::fromPem($offlineCertificatePrivateKeyPem)
);

$bytes = $certificateQr->bytes();
```

KOD II signing follows the MF rules: RSA uses RSASSA-PSS with SHA-256, MGF1 SHA-256, and 32-byte salt; ECDSA uses P-256/SHA-256 and IEEE P1363 signature encoding.
