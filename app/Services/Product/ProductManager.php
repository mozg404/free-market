<?php

namespace App\Services\Product;

use App\Exceptions\Product\NotEnoughStockException;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockItem;
use Illuminate\Database\Eloquent\Collection;

class ProductManager
{
    /**
     * Выбрасывает исключение, если у товара $product нет $quantity доступных позиций на складе
     */
    public function ensureStockAvailable(Product $product, int $quantity = 1): void
    {
        if (!$this->checkStockAvailable($product, $quantity)) {
            throw new NotEnoughStockException();
        }
    }

    /**
     * Проверяет наличие доступных позиций для продажи у товара
     */
    public function checkStockAvailable(Product $product, int $quantity = 1): bool
    {
        return $product->stockItems()->isAvailable()->count() >= $quantity;
    }

    /**
     * Возвращает $quantity количество доступных для продажи позиций товара $product
     * @param Product $product
     * @param int $quantity
     * @return Collection
     */
    public function getAvailableStockItemsFor(Product $product, int $quantity = 1): Collection
    {
        $items = $product->stockItems()
            ->isAvailable()
            ->take($quantity)
            ->get();

        if ($items->count() < $quantity) {
            throw new NotEnoughStockException();
        }

        return $items;
    }

    /**
     * Резервирует позицию на складе за заказом
     */
    public function reserveStockItem(StockItem $stockItem, Order $order): void
    {
        $stockItem->reserve($order->user);
    }
}