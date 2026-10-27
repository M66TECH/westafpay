<?php

namespace Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Checkout;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetCheckoutByTransactionId extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $transaction_id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v1/checkout/sessions';
    }

    protected function defaultQuery(): array
    {
        return [
            'transaction_id' => $this->transaction_id,
        ];
    }
}
