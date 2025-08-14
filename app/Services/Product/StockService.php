<?php

namespace App\Services\Product;

use App\Enum\StockItemStatus;
use App\Exceptions\Product\NotEnoughStockException;
use App\Models\Product;
use App\Models\StockItem;

class StockService
{
    public function hasEnoughStock(Product $product, int $quantity = 1): bool
    {
        return $this->getAvailableCount($product) >= $quantity;
    }

    public function ensureStockAvailable(Product $product, int $quantity = 1): void
    {
        if (!$this->hasEnoughStock($product, $quantity)) {
            throw new NotEnoughStockException();
        }
    }

    public function getAvailableCount(Product $product): int
    {
        return $product->stockItems()->isAvailable()->count();
    }

    public function cancelReservation(StockItem $stockItem): void
    {
        $stockItem->status = StockItemStatus::AVAILABLE;
        $stockItem->save();
    }
}