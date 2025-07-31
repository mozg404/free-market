<?php

namespace App\Services;

use App\Data\Cart\CartItemData;
use App\Enum\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\Cart\CartManager;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderManager
{
    public function __construct(
        private readonly CartManager  $cart,
        private readonly StockManager $stock,
    )
    {}

    /**
     * Создает новый заказ и резервирует позиции товаров на складе
     * @return Order
     * @throws \Throwable
     */
    public function create(): Order
    {
        return DB::transaction(function () {
            $cart = $this->cart->all();

            // (!!!) Добавить проверку на доступность позиций на складе и выброс эксэпшена
            // (!!!) Добавить проверку на доступность самого товара по статусу

            // Создание заказа
            $order = Order::create([
                'user_id' => Auth::id(),
                'status' => OrderStatus::NEW,
                'total_price' => $cart->amount,
            ]);

            // Создание позиций заказа и резервирование позиций на складе
            foreach ($cart->items as $item) {
                $product = Product::find($item->product->id);
                $stockItems = $product
                    ->stockItems()
                    ->isAvailable()
                    ->take($item->quantity)
                    ->get();

                foreach ($stockItems as $stockItem) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'stock_item_id' => $stockItem->id,
                        'price' => $item->product->price->getCurrent(),
                    ]);

                    $this->stock->reserve($stockItem);
                }
            }

            // Сброс корзины
            $this->cart->clean();

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