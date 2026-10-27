<?php

namespace Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Checkout;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetCheckout extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v1/checkout/sessions/'.$this->id;
    }
}
