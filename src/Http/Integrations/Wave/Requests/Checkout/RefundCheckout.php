<?php

namespace Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Checkout;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class RefundCheckout extends Request
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return '/v1/checkout/sessions/'.$this->transaction_id.'/refund';
    }

    public function __construct(
        protected readonly string $transaction_id,
    ) {}

}
