<?php

namespace Laravelsn\Westafpay\DataTransferObjects\Wave\Transaction;

class PageInfoDto
{
    public function __construct(
        public readonly ?string $startCursor,
        public readonly string $endCursor,
        public readonly bool $hasNextPage
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            startCursor: $data['start_cursor'],
            endCursor: $data['end_cursor'],
            hasNextPage: $data['has_next_page']
        );
    }

    public function toArray(): array
    {
        return [
            'start_cursor' => $this->startCursor,
            'end_cursor' => $this->endCursor,
            'has_next_page' => $this->hasNextPage,
        ];
    }
}
