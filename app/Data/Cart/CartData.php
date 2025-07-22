<?php

namespace App\Data\Cart;

use Spatie\LaravelData\Data;

/**
 * @property
 */
class CartData extends Data
{
    public int $totalPrice = 0;
    public int $totalCount;

    public function __construct(
        /** @var CartItemData[] $items */
        public array $items = [],
    ) {
        $this->totalCount = count($this->items);

        foreach ($items as $item) {
            $this->totalPrice += $item->totalPrice;
        }
    }
}
