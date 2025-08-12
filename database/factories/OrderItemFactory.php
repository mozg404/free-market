<?php

namespace Database\Factories;

use App\Models\OrderItem;
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
            'stock_item_id' => StockItem::factory(),
            'current_price' => $price->getCurrentPrice(),
            'base_price' => $price->getBasePrice(),
        ];
    }
}
