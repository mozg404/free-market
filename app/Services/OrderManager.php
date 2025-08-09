<?php

namespace App\Services;

use App\Data\Cart\CartData;
use App\Enum\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderManager
{
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
            $order = Order::new($user, $cart->amount->getCurrentPrice());

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
                        'current_price' => $product->price->getCurrentPrice(),
                        'base_price' => $product->price->getBasePrice(),
                    ]);
                    $stockItem->reserve($user);
                }
            }

            return $order;
        });
    }
}