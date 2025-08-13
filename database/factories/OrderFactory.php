<?php

namespace Database\Factories;

use App\Enum\OrderStatus;
use App\Enum\StockItemStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StockItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $createdAt = $this->faker->dateTimeBetween('-1 year');

        return [
            'user_id' => User::factory(),
            'amount' => 0, // Будет пересчитано автоматически
            'status' => $this->faker->randomElement(OrderStatus::cases()),
            'paid_at' => null,
            'created_at' => $createdAt,
            'updated_at' => $this->faker->dateTimeBetween($createdAt),
        ];
    }

    public function withItems(): OrderFactory
    {
        return $this->afterCreating(function (Order $order) {
            OrderItemFactory::new()
                ->count($this->faker->numberBetween(1, 3))
                ->for($order)
                ->create();

            $order->update([
                'amount' => $order->items->sum('current_price')
            ]);

            if ($order->status === OrderStatus::PAID && !$order->paid_at) {
                $order->update(['paid_at' => now()]);
            }
        });
    }

    public function paid(): static
    {
        return $this->state([
            'status' => OrderStatus::PAID,
            'paid_at' => now(),
        ]);
    }

    public function asNew(): static
    {
        return $this->state([
            'status' => OrderStatus::NEW,
            'paid_at' => null,
        ]);
    }

    public function cancelled(): static
    {
        return $this->state([
            'status' => OrderStatus::CANCELLED,
            'paid_at' => null,
        ]);
    }

    public function withStockItems(Collection $stockItems): static
    {
        return $this->afterCreating(function (Order $order) use ($stockItems) {
            $amount = 0;

            $stockItems->each(function (StockItem $stockItem) use ($order, &$amount) {
                $product = $stockItem->product;

                OrderItem::create([
                    'order_id' => $order->id,
                    'stock_item_id' => $stockItem->id,
                    'current_price' => $product->current_price,
                    'base_price' => $product->base_price,
                ]);

                $amount += $product->current_price;
                $stockItem->update([
                    'status' => $order->isNew() ? StockItemStatus::RESERVED->value : StockItemStatus::SOLD->value,
                    'order_id' => $order->id,
                ]);
            });

            $order->update(['amount' => $amount]);
        });
    }
}
