<?php

namespace Laravelsn\Westafpay\DataTransferObjects\Wave;

use Laravelsn\Westafpay\DataTransferObjects\Wave\Transaction\PageInfoDto;
use Laravelsn\Westafpay\DataTransferObjects\Wave\Transaction\TransactionItemDto;

class TransactionResponseData
{
    public function __construct(
        public readonly PageInfoDto $pageInfo,
        public readonly string $date,
        public readonly array $items
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            pageInfo: PageInfoDto::fromArray($data['page_info']),
            date: $data['date'],
            items: array_map(
                fn ($item) => TransactionItemDto::fromArray($item),
                $data['items']
            )
        );
    }

    public function toArray(): mixed
    {
        return [
            'page_info' => $this->pageInfo->toArray(),
            'date' => $this->date,
            'items' => array_map(
                fn ($item) => $item->toArray(),
                $this->items
            ),
        ];
    }
}
