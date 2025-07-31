<?php

namespace App\Data\Cart;

use App\Support\Price;
use Spatie\LaravelData\Data;

/**
 * @property
 */
class CartData extends Data
{
    /** @var int Сумма к оплате */
    public Price $amount;

    /** @var int Количество уникальный товаров */
    public int $count = 0;

    /** @var int Общее количество товаров */
    public int $quantity = 0;

    public function __construct(
        /** @var CartItemData[] $items */
        public array $items = [],
    ) {
        $this->count = count($this->items);

        foreach ($items as $item) {
            if (!isset($this->amount)) {
                $this->amount = $item->amount->clone();
            } else {
                $this->amount = $this->amount->sumWith($item->amount);
            }

            $this->quantity += $item->quantity;
        }
    }
}
