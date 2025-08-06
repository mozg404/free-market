<?php

namespace App\Data\Orders;

use App\Enum\OrderStatus;
use App\Models\Order;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class OrderData extends Data
{
    public function __construct(
        public int $id,
        public int $amount,
        public OrderStatus $status,
        /** @var OrderItemData[] */
        public array $items,
        public int $items_count,
        public Carbon $created_at,
    ) {
    }

}
