<?php

namespace App\Services\Cart;

use App\Contracts\Cart;
use App\Data\Cart\CartData;
use App\Data\Cart\CartItemData;
use App\Data\Products\ProductPreviewData;
use App\Models\Product;
use App\Services\Product\StockService;

class CartService
{
    public function __construct(
        private Cart $cart,
        private StockService $stockService,
    ) {
    }

    public function add(Product $product, int $quantity = 1): void
    {
        $this->stockService->ensureStockAvailable($product, $this->cart->getQuantityFor($product->id) + $quantity);

        $this->cart->add($product->id, $quantity);
    }

    public function remove(Product $product, int $quantity = 1)
    {
        $this->cart->remove($product->id, $quantity);
    }

    public function has(Product $product): bool
    {
        return $this->cart->has($product->id);
    }

    public function clear()
    {
        $this->cart->clear();
    }

    public function removeItem(Product $product)
    {
        $this->cart->removeItem($product->id);
    }

    public function getItem(Product $product): CartItemData|null
    {
        if (!$this->cart->has($product->id)) {
            return null;
        }

        $quantity = $this->cart->getQuantityFor($product->id);

        return new CartItemData(
            id: $product->id,
            name: $product->name,
            price: $product->price,
            preview: ProductPreviewData::from($product->preview),
            status: $product->status,
            created_at: $product->created_at,
            available_stock_items_count: $this->stockService->getAvailableCount($product),
            quantity: $quantity,
        );
    }

    public function isEmpty(): bool
    {
        return $this->cart->isEmpty();
    }

    public function getItems(): CartData
    {
        $items = [];

        Product::query()
            ->whereIds($this->cart->getIds())
            ->withAvailableStockItemsCount()
            ->get()->each(function (Product $product) use (&$items) {
                $items[] = new CartItemData(
                    id: $product->id,
                    name: $product->name,
                    price: $product->price,
                    preview: ProductPreviewData::from($product->preview),
                    status: $product->status,
                    created_at: $product->created_at,
                    available_stock_items_count: $product->available_stock_items_count,
                    quantity: $this->cart->getQuantityFor($product->id),
                );
            });

        return new CartData($items);
    }
}