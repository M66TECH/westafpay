<?php

namespace Laravelsn\Westafpay\Http\Integrations\Wave\Requests\Reconciliation;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\PaginationPlugin\Contracts\Paginatable;

class Transaction extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly ?string $date = null,
        protected readonly ?bool $includeSubaccounts = null,
        protected readonly ?int $first = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v1/transactions';
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'date' => $this->date,
            'include_subaccounts' => $this->includeSubaccounts === null ? null : ($this->includeSubaccounts ? 'true' : 'false'),
            'first' => $this->first,
        ], static fn (mixed $value): bool => $value !== null);
    }
}
