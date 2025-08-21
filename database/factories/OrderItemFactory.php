<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StockItem;
use App\Support\Price;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        $price = Price::random();

        return [
            'product_id' => Product::factory(),
            'stock_item_id' => StockItem::factory(),
            'current_price' => $price->getCurrentPrice(),
            'base_price' => $price->getBasePrice(),
        ];
    }

    public function configure(): self
    {
        return $this->afterCreating(function (OrderItem $orderItem) {
            if ($orderItem->product_id !== $orderItem->stockItem->product_id) {
                $orderItem->update([
                    'product_id' => $orderItem->stockItem->product_id,
                ]);
            }
        });
    }
}
