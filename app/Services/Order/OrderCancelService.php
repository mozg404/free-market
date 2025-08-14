<?php

namespace App\Services\Order;

use App\Enum\OrderStatus;
use App\Exceptions\Order\CompletedOrderCannotBeCanceledException;
use App\Exceptions\Order\OrderAlreadyCanceledException;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\Product\StockService;
use Illuminate\Support\Facades\DB;

readonly class OrderCancelService
{
    public function __construct(
        private StockService $stockService,
    ) {
    }

    public function cancelOrder(Order $order)
    {
        if ($order->isCompleted()) {
            throw new CompletedOrderCannotBeCanceledException();
        }

        if ($order->isCancelled()) {
            throw new OrderAlreadyCanceledException();
        }

        // Меняем статус заказа на отмене
        DB::transaction(function () use ($order) {
            $order->status = OrderStatus::CANCELLED;
            $order->save();

            $order->items->each(function (OrderItem $item) {
                $this->stockService->cancelReservation($item->stockItem);
            });
        });
    }
}