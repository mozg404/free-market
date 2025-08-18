<?php

namespace App\Data\Products;

use App\Data\Categories\CategorydData;
use App\Data\User\UserShortData;
use App\Enum\ProductStatus;
use App\Models\OrderItem;
use App\Support\Price;
use Carbon\Carbon;
use Spatie\LaravelData\Data;

class ProductPurchasedData extends Data
{
    public function __construct(
        public int $id,
        public int $order_id,
        public int $stock_item_id,
        public string $name,
        public Price $price,
        public ?string $image_url,
        public ?CategorydData $category,
        public ProductStatus $status,
        public ?UserShortData $seller = null,
        public Carbon $purchased_at,
    ) {}

    public static function fromModel(OrderItem $orderItem): self
    {
        return new self(
            id: $orderItem->stockItem->product_id,
            order_id: $orderItem->order_id,
            stock_item_id: $orderItem->stock_item_id,
            name: $orderItem->stockItem->product->name,
            price: $orderItem->price,
            image_url: $orderItem->stockItem->product->image_url,
            category: CategorydData::from($orderItem->stockItem->product->category),
            status: $orderItem->stockItem->product->status,
            seller: UserShortData::from($orderItem->stockItem->product->user),
            purchased_at: $orderItem->order->paid_at
        );
    }
}
