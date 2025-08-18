<?php

namespace App\Data\Cart;

use App\Enum\ProductStatus;
use App\Support\Price;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

class CartItemData extends Data
{
    public Price $amount;

    public function __construct(
        public int $id,
        public string $name,
        public Price $price,
        public ?string $image_url = null,
        public ProductStatus $status,
        public Carbon $created_at,
        public ?int $available_stock_items_count = null,
        public int $quantity = 1,
    ) {
        $this->amount = $this->price->multiply($this->quantity);
    }
}
