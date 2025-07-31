<?php

namespace App\Data;

use App\Enum\OrderStatus;
use App\Models\Order;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

class OrderViewData extends Data
{
    public function __construct(
        public int         $id,
        public int         $amount,
        public OrderStatus $status,
        public bool        $isNew,
        public bool        $isPaid,
        public int         $itemsCount,
        public Carbon      $createdAt,
    ) {}

    public static function fromModel(Order $order): static
    {
        return new self(
            id: $order->id,
            amount: $order->amount,
            status: $order->status,
            isNew: $order->isNew(),
            isPaid: $order->isPaid(),
            itemsCount: $order->items_count,
            createdAt: $order->created_at,
        );
    }
}
