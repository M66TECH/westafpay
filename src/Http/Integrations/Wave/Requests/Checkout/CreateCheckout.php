<?php

namespace Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Checkout;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CreateCheckout extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected array $payload
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v1/checkout/sessions';
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
        ];
    }

    public function defaultBody(): array
    {
        return $this->payload;
    }
}
