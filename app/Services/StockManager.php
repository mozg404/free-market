<?php

namespace App\Services;

use App\Enum\StockItemStatus;
use App\Models\Product;
use App\Models\StockItem;
use App\Models\User;

class StockManager
{
    /**
     * Добавляет позицию товара на склад
     * @param Product $product
     * @param string $content
     * @return StockItem
     */
    public function addItemTo(Product $product, string $content): StockItem
    {
        $stockItem = new StockItem();
        $stockItem->product_id = $product->id;
        $stockItem->content = $content;
        $stockItem->status = StockItemStatus::AVAILABLE;
        $stockItem->save();

        return $stockItem;
    }

    public function updateItem(StockItem $item, string $content): StockItem
    {
        $item->content = $content;
        $item->save();

        return $item;
    }

    public function reserve(StockItem $item): StockItem
    {
        $item->status = StockItemStatus::RESERVED;
        $item->save();

        return $item;
    }

    public function buy(StockItem $item, User $user): StockItem
    {
        $item->status = StockItemStatus::SOLD;
        $this->buyer_id = $user->id;
        $item->save();

        return $item;
    }

    public function delete(StockItem $item): void
    {
        $item->delete();
    }
}