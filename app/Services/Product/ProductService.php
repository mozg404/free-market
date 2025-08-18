<?php

namespace App\Services\Product;

use App\Enum\ProductStatus;
use App\Exceptions\Product\ProductUnavailableException;
use App\Exceptions\Product\NotEnoughStockException;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockItem;
use App\Models\User;
use App\Support\Image;
use App\Support\Price;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function createProduct(User $user, Category $category, string $name, Price $price): Product
    {
        $product = new Product();
        $product->user_id = $user->id;
        $product->category_id = $category->id;
        $product->status = ProductStatus::DRAFT;
        $product->name = $name;
        $product->price = $price;
        $product->save();

        return $product;
    }

    public function changeName(Product $product, string $newName): void
    {
        $product->name = $newName;
        $product->save();
    }

    public function changeCategory(Product $product, Category $category): void
    {
        $product->category_id = $category->id;
        $product->save();
    }

    public function changePrice(Product $product, Price $price): void
    {
        $product->price = $price;
        $product->save();
    }

    public function changeDescription(Product $product, string $description): void
    {
        $product->description = $description;
        $product->save();
    }

    public function changeFeatures(Product $product, array $features): void
    {
        DB::transaction(static function () use ($product, $features) {
            $product->features()->detach();

            foreach ($features as $id => $value) {
                if (!empty($value)) {
                    $product->features()->attach($id, ['value' => $value]);
                }
            }
        });
    }

    public function changeImage(Product $product, Image $image): void
    {
        if ($image->getUrl() !== $product->image?->getUrl()) {
            if (isset($product->image)) {
                $product->image->delete();
            }

            $product->image = $image->publishIfTemporary();
            $product->save();
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