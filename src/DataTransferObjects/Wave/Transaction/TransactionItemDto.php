<?php

namespace Laravelsn\Westafpay\DataTransferObjects\Wave\Transaction;

class TransactionItemDto
{
    public function __construct(
        public readonly string $timestamp,
        public readonly string $transactionId,
        public readonly string $amount,
        public readonly string $fee,
        public readonly string $currency,
        public readonly ?string $counterpartyName,
        public readonly ?string $counterpartyMobile,
        public readonly ?bool $isReversal = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            timestamp: $data['timestamp'],
            transactionId: $data['transaction_id'],
            amount: $data['amount'],
            fee: $data['fee'],
            currency: $data['currency'],
            counterpartyName: $data['counterparty_name'] ?? null,
            counterpartyMobile: $data['counterparty_mobile'] ?? null,
            isReversal: $data['is_reversal'] ?? null
        );
    }

    public function toArray(): array
    {
        $array = [
            'timestamp' => $this->timestamp,
            'transaction_id' => $this->transactionId,
            'amount' => $this->amount,
            'fee' => $this->fee,
            'currency' => $this->currency,
        ];

        if ($this->counterpartyName !== null) {
            $array['counterparty_name'] = $this->counterpartyName;
        }

        if ($this->counterpartyMobile !== null) {
            $array['counterparty_mobile'] = $this->counterpartyMobile;
        }

        if ($this->isReversal !== null) {
            $array['is_reversal'] = $this->isReversal;
        }

        return $array;
    }
}
