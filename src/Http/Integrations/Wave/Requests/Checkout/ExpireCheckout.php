<?php

namespace Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Checkout;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class ExpireCheckout extends Request
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return '/v1/checkout/sessions/'.$this->transaction_id.'/expire';
    }

    public function __construct(
        protected readonly string $transaction_id,
    ) {}
}
