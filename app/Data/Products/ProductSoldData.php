<?php

namespace App\Data\Products;

use App\Data\Categories\CategorydData;
use App\Data\User\UserShortData;
use App\Enum\ProductStatus;
use App\Models\OrderItem;
use App\Support\Price;
use Carbon\Carbon;
use Spatie\LaravelData\Data;

class ProductSoldData extends Data
{
    public function __construct(
        public int $id,
        public int $order_id,
        public int $stock_item_id,
        public string $name,
        public Price $price,
        public ?string $preview_image,
        public ProductStatus $status,
        public ?UserShortData $buyer = null,
        public Carbon $sold_at,
    ) {}

    public static function fromModel(OrderItem $orderItem): self
    {
        return new self(
            id: $orderItem->stockItem->product_id,
            order_id: $orderItem->order_id,
            stock_item_id: $orderItem->stock_item_id,
            name: $orderItem->stockItem->product->name,
            price: $orderItem->price,
            preview_image: $orderItem->stockItem->product->preview_image,
            status: $orderItem->stockItem->product->status,
            buyer: UserShortData::from($orderItem->order->user),
            sold_at: $orderItem->order->paid_at
        );
    }
}
