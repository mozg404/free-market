<?php

namespace App\Data\Cart;

use App\Data\Products\ProductData;
use App\Support\Price;
use Spatie\LaravelData\Data;

class CartItemData extends Data
{
    public Price $amount;

    public function __construct(
        public ProductData $product,
        public int $quantity,
    ) {
        $this->amount = $product->price->multiply($this->quantity);
    }
}
