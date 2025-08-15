<?php

namespace App\Services\Product;

use App\Exceptions\Product\ProductUnavailableException;
use App\Exceptions\Product\NotEnoughStockException;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockItem;
use App\Models\User;
use App\Support\Price;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    public function createProduct(User $user, string $name, Price $price): Product
    {
        $product = new Product();
        $product->user_id = $user->id;
        $product->name = $name;
        $product->price = $price;
        $product->save();

        return $product;
    }


    /**
     * Проверяет наличие доступных позиций для продажи у товара
     */
    public function checkStockAvailable(Product $product, int $quantity = 1): bool
    {
        return $product->stockItems()->isAvailable()->count() >= $quantity;
    }

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
     * @throws ProductUnavailableException
     */
    public function ensureIsActiveProduct(Product $product): void
    {
        if (!$product->isActive()) {
            throw new ProductUnavailableException();
        }
    }

    /**
     * @throws ProductUnavailableException
     */
    public function ensureCanByPurchased(Product $product, int $quantity = 1): void
    {
        $this->ensureIsActiveProduct($product);
        $this->ensureStockAvailable($product, $quantity);
    }

    /**
     * Возвращает коллекцию позиций на складе в количестве $quantity для товара $product
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
        $stockItem->reserveFor($order);
    }
}