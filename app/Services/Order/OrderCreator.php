<?php

namespace App\Services\Order;

use App\Collections\CreatableOrderItemCollection;
use App\Data\Orders\CreatableOrderItemData;
use App\Models\Order;
use App\Models\StockItem;
use App\Models\User;
use App\Services\Product\ProductManager;

readonly class OrderCreator
{
    public function __construct(
        private ProductManager $productManager,
    ) {
    }

    /**
     * Создает новый заказ
     * @param User $user
     * @param CreatableOrderItemCollection $items
     * @return Order
     */
    public function create(User $user, CreatableOrderItemCollection $items): Order
    {
        // Проверяем, что у товаров из списка есть нужное количество позиций на складе
        $items->each(fn(CreatableOrderItemData $item) => $this->productManager->ensureStockAvailable($item->product, $item->quantity));

        // Создаем новый заказ
        $order = Order::new($user, $items->getTotalPrice());

        $items->each(function (CreatableOrderItemData $item) use ($order) {
            // Запрашиваем у менеджера свободные позиции со склада
            $stockItems = $this->productManager->getAvailableStockItemsFor($item->product, $item->quantity);

            // Пробегаемся по позициям и резервируем их для заказа
            $stockItems->each(function (StockItem $stockItem) use ($item, $order) {
                // Создаем позицию заказа
                $order->createItem($stockItem, $item->product->price);

                // Резервируем товар на складе
                $this->productManager->reserveStockItem($stockItem, $order);
            });
        });

        return $order->fresh();
    }
}