<?php

namespace Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Checkout;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class SearchForCheckout extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/v1/checkout/sessions/search';
    }

    public function __construct(
        protected readonly string $client_reference,
    ) {}

    protected function defaultQuery(): array
    {
        return [
            'client_reference' => $this->client_reference,
        ];
    }
}
