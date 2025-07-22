<?php

namespace App\Services;

use App\Data\Cart\CartItemData;
use App\Enum\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\Cart\CartManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderManager
{
    public function __construct(
        private CartManager $cart,
    )
    {}

    public function create(): Order
    {
        return DB::transaction(function () {
            $cart = $this->cart->all();

            // Создаем заказ (со статусом new)
            $order = Order::create([
                'user_id' => Auth::id(),
                'status' => OrderStatus::NEW,
                'total_price' => $cart->totalPrice,
            ]);

            // Пробегаемся по корзине и создаем позиции заказа
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product->id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price->getCurrent(),
                ]);
            }

            // Очищаем корзину
//        $this->cart->clean();

            // Возвращаем заказ
            return $order;
        });
    }
}