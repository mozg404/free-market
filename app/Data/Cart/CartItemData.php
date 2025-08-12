<?php

namespace App\Data\Cart;

use App\Data\Products\ProductForListingData;
use App\Support\Price;
use Spatie\LaravelData\Data;

class CartItemData extends Data
{
    public Price $amount;

    public function __construct(
        public ProductForListingData $product,
        public int $quantity = 1,
    ) {
        $this->amount = $product->price->multiply($this->quantity);
    }
}
