<?php

namespace App\Services\Order;

use App\Collections\CreatableOrderItemCollection;
use App\Data\Orders\CreatableOrderItemData;
use App\Enum\OrderStatus;
use App\Exceptions\Product\ProductUnavailableException;
use App\Models\Order;
use App\Models\StockItem;
use App\Models\User;
use App\Services\Product\ProductService;
use Illuminate\Support\Facades\DB;
use Throwable;

readonly class OrderCreator
{
    public function __construct(
        private ProductService $productManager,
    ) {
    }

    /**
     * Создает новый заказ
     * @param User $user
     * @param CreatableOrderItemCollection $items
     * @return Order
     * @throws ProductUnavailableException|Throwable
     */
    public function create(User $user, CreatableOrderItemCollection $items): Order
    {
        // Проверяем, что у товаров из списка есть нужное количество позиций на складе
        $items->each(fn(CreatableOrderItemData $item) => $this->productManager->ensureCanByPurchased($item->product, $item->quantity));

        // Создаем новый заказ
        return DB::transaction(function () use ($user, $items) {
            $order = Order::create([
                'user_id' => $user->id,
                'amount' => $items->getTotalPrice()->getCurrentPrice(),
                'status' => OrderStatus::PENDING,
            ]);

            $items->each(function (CreatableOrderItemData $creatableItem) use ($order) {
                // Запрашиваем у менеджера свободные позиции со склада
                $stockItems = $this->productManager->getAvailableStockItemsFor($creatableItem->product, $creatableItem->quantity);

                // Пробегаемся по позициям и резервируем их для заказа
                $stockItems->each(function (StockItem $stockItem) use ($creatableItem, $order) {
                    // Создаем позицию заказа
                    $orderItem = $order->items()->create([
                        'product_id' => $creatableItem->product->id,
                        'stock_item_id' => $stockItem->id,
                        'current_price' => $creatableItem->product->price->getCurrentPrice(),
                        'base_price' => $creatableItem->product->price->getBasePrice(),
                    ]);

                    // Резервируем товар на складе
                    $this->productManager->reserveStockItem($stockItem, $orderItem);
                });
            });

            return $order->fresh();
        });
    }
}