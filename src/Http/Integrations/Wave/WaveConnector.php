<?php

namespace Laravelsn\Westafpay\Http\Integrations\Wave;

use Saloon\Http\Connector;
use Saloon\Http\PendingRequest;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\HasPagination;
use Saloon\PaginationPlugin\Paginator;
use Saloon\Traits\Plugins\HasTimeout;
use Stringable;

class WaveConnector extends Connector implements HasPagination
{
    use HasTimeout;

    protected int $connectTimeout = 10;

    protected int $requestTimeout = 30;

    /**
     * Build the Wave-Signature header value for the given body and timestamp.
     */
    public static function signature(string $secret, string $body, ?int $timestamp = null): string
    {
        $timestamp ??= time();

        return sprintf('t=%s,v1=%s', $timestamp, hash_hmac('sha256', $timestamp.$body, $secret));
    }

    public function __construct()
    {
        $this->middleware()->onRequest(function (PendingRequest $pendingRequest): void {
            $secret = config('westafpay.wave.signing_secret');

            if (blank($secret)) {
                return;
            }

            $body = $pendingRequest->body();

            $pendingRequest->headers()->add(
                'Wave-Signature',
                self::signature($secret, $body instanceof Stringable ? (string) $body : ''),
            );
        });
    }

    public function resolveBaseUrl(): string
    {
        return config('westafpay.wave.base_url');
    }

    protected function defaultHeaders(): array
    {
        return [
            'Authorization' => 'Bearer '.config('westafpay.wave.api_key'),
            'Accept' => 'application/json',
        ];
    }

    public function paginate(Request $request): Paginator
    {
        return new class(connector: $this, request: $request) extends Paginator
        {
            protected ?string $endCursor = null;

            protected function isLastPage(Response $response): bool
            {
                return $response->json('page_info.has_next_page') === false;
            }

            protected function getPageItems(Response $response, Request $request): array
            {
                $this->endCursor = $response->json('page_info.end_cursor');

                return $response->json('items') ?? [];
            }

            protected function applyPagination(Request $request): Request
            {
                if ($this->endCursor !== null) {
                    $request->query()->add('after', $this->endCursor);
                }

                return $request;
            }
        };
    }
}
