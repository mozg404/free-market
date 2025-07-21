<?php

namespace App\Data\Cart;

use App\Data\Products\ProductData;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class CartData extends Data
{
    public int $totalPrice = 0;
    public int $totalCount;

    public function __construct(
        /** @var CartItemData<array> $items */
        public array $items = [],
    ) {
        $this->totalCount = count($this->items);

        foreach ($items as $item) {
            $this->totalPrice += $item->totalPrice;
        }
    }
}
