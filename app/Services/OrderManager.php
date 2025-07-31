<?php

namespace App\Services;

use App\Data\Cart\CartData;
use App\Data\Cart\CartItemData;
use App\Enum\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Services\Cart\CartManager;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderManager
{
    public function __construct(
        private readonly StockManager $stock,
    )
    {}

    /**
     * Создает новый заказ и резервирует позиции товаров на складе
     * @param User $user
     * @param CartData $cart
     * @return Order
     * @throws \Throwable
     */
    public function create(User $user, CartData $cart): Order
    {
        return DB::transaction(function () use ($user, $cart) {
            // (!!!) Добавить проверку на доступность позиций на складе и выброс эксэпшена
            // (!!!) Добавить проверку на доступность самого товара по статусу

            // Создание заказа
            $order = Order::new($user, $cart->amount->getCurrent());

            // Создание позиций заказа и резервирование позиций на складе
            foreach ($cart->items as $item) {
                $product = Product::find($item->product->id);
                $stockItems = $product->stockItems()
                    ->isAvailable()
                    ->take($item->quantity)
                    ->get();

                foreach ($stockItems as $stockItem) {
                    $order->items()->create([
                        'stock_item_id' => $stockItem->id,
                        'price_base' => $product->price->base,
                        'price_discount' => $product->price->discount,
                    ]);
                    $stockItem->reserve();
                }
            }

            return $order;
        });
    }

    /**
     * Помечает заказ, как оплаченный + помечает позиции на складе как выкупленные
     * @param Order $order
     * @return Order
     * @throws \Throwable
     */
    public function pay(Order $order): Order
    {
        return DB::transaction(function () use ($order) {
            // Меняем статус заказа
            $order->status = OrderStatus::PAID;
            $order->paid_at = Carbon::now();
            $order->save();

            // Меняем статус позиций на складе на оплачено
            foreach ($order->items as $item) {
                $this->stock->buy($item->stockItem, $order->user);
            }

            return $order;
        });
    }
}