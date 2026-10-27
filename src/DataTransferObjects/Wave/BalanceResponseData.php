<?php

namespace Laravelsn\Westafpay\DataTransferObjects\Wave;

class BalanceResponseData
{
    public function __construct(
        public readonly string $balance,
        public readonly string $currency,
    ) {}
}
