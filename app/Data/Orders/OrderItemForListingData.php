<?php

namespace App\Data\Orders;

use App\Data\Products\ProductForListingData;
use App\Models\OrderItem;
use App\Models\Product;
use App\Support\Price;
use Spatie\LaravelData\Data;

class OrderItemForListingData extends Data
{
    public function __construct(
        public int $id,
        public int $order_id,
        public Price $price,
        public ProductForListingData $product,
    ) {
    }

    public static function fromModel(OrderItem $orderItem): static
    {
        return new self(
            id: $orderItem->id,
            order_id: $orderItem->order_id,
            price: $orderItem->price,
            product: ProductForListingData::from($orderItem->stockItem->product),
        );
    }
}
