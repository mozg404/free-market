<?php

namespace Database\Factories;

use App\Enum\OrderStatus;
use App\Models\Order;
use App\Models\User;
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

    public function configure(): OrderFactory
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
}
