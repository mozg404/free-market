<?php

namespace App\Data\Cart;

use App\Data\Products\ProductData;
use Spatie\LaravelData\Data;

class CartItemData extends Data
{
    public int $totalPrice;

    public function __construct(
        public ProductData $product,
        public int $quantity,
    ) {
        $this->totalPrice = $product->price->calculatePriceByQuantity($this->quantity);
    }
}
