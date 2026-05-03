<?php

declare(strict_types=1);

namespace Nozu\KsefClient\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Nozu\KsefClient\ClientOptions;
use Nozu\KsefClient\Exception\KsefApiException;
use Nozu\KsefClient\Http\Headers;
use Nozu\KsefClient\Http\JsonBody;
use Nozu\KsefClient\Http\QueryParameters;
use Nozu\KsefClient\Http\StringList;
use Nozu\KsefClient\KsefClient;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

final class KsefClientTest extends TestCase
{
    public function testItSendsBearerJsonAndInterpolatesPathThroughSessionResource(): void
    {
        $history = [];
        $client = $this->client(
            [new Response(200, ['Content-Type' => 'application/json'], '{"ok":true}')],
            $history,
            'token'
        );

        $response = $client->sessions()->sendOnlineInvoice(
            'SESSION/1',
            JsonBody::fromObject((object) ['invoiceHash' => 'abc'])
        );

        self::assertTrue($response->json()?->object()?->ok);
        self::assertCount(1, $history);

        /** @var RequestInterface $request */
        $request = $history[0]['request'];
        self::assertSame('POST', $request->getMethod());
        self::assertSame('/v2/sessions/online/SESSION%2F1/invoices', $request->getUri()->getPath());
        self::assertSame('Bearer token', $request->getHeaderLine('Authorization'));
        self::assertSame('application/json', $request->getHeaderLine('Content-Type'));
        self::assertSame('{"invoiceHash":"abc"}', (string) $request->getBody());
    }

    public function testItKeepsContinuationTokenAsHeaderAndRepeatsArrayQueryValues(): void
    {
        $history = [];
        $client = $this->client([new Response(200, [], '{}')], $history);

        $client->sessions()->list(
            QueryParameters::empty()
                ->withString('sessionType', 'Online')
                ->withValues('statuses', new StringList('InProgress', 'Succeeded'))
                ->withInt('pageSize', 10),
            Headers::continuationToken('next-page')
        );

        /** @var RequestInterface $request */
        $request = $history[0]['request'];
        self::assertSame('sessionType=Online&statuses=InProgress&statuses=Succeeded&pageSize=10', $request->getUri()->getQuery());
        self::assertSame('next-page', $request->getHeaderLine('x-continuation-token'));
    }

    public function testItThrowsApiExceptionWithProblemDetails(): void
    {
        $client = $this->client([
            new Response(400, ['Content-Type' => 'application/problem+json'], '{"title":"Bad request"}'),
        ]);

        $this->expectException(KsefApiException::class);
        $this->expectExceptionMessage('KSeF API error 400: Bad request');

        $client->auth()->createChallenge();
    }

    /**
     * @param list<Response> $responses
     * @param list<array<string, mixed>> $history
     */
    private function client(array $responses, array &$history = [], ?string $token = null): KsefClient
    {
        $mock = new MockHandler($responses);
        $stack = HandlerStack::create($mock);
        $stack->push(Middleware::history($history));

        $http = new Client([
            'base_uri' => 'https://example.test/v2/',
            'handler' => $stack,
            'http_errors' => false,
        ]);

        return KsefClient::create(new ClientOptions('https://example.test/v2', $token, $http));
    }
}
