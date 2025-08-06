<?php

namespace App\Data;

use App\Data\User\UserData;
use App\Models\OrderItem;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

class PurchasedProductViewData extends Data
{
    public function __construct(
        public int $id,
        public int $orderId,
        public int $orderItemId,
        public int $productId,
        public int $stockItemId,
        public string $name,
        public ImageData $image,
        public int $price,
        public UserData $buyer,
        public UserData $seller,
        public Carbon $purchasedAt,
    ) {}

    public static function fromModel(OrderItem $item): self
    {
        return new self(
            id: $item->id,
            orderId: $item->order_id,
            orderItemId: $item->id,
            productId: $item->stockItem->product->id,
            stockItemId: $item->stock_item_id,
            name: $item->stockItem->product->name,
            image: ImageData::from($item->stockItem->product->preview_image),
            price: $item->price,
            buyer: UserData::from($item->order->user),
            seller: UserData::from($item->stockItem->product->user),
            purchasedAt: $item->order->paid_at,
        );
    }
}
