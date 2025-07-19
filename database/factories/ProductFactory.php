<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    private array $images = [
        'products/2025-03/7013559385.webp',
        'products/2025-03/7144259611.webp',
        'products/2025-03/7193086303.webp',
        'products/2025-03/7378302240.webp',
        'products/2025-03/7380478121.webp',
        'products/2025-03/7417857989.webp',
        'products/2025-03/7433850176.webp',
        'products/2025-03/7466314066.webp',
        'products/2025-03/7482027756.webp',
        'products/2025-03/7490797120.webp',
        'products/2025-04/7228947268.webp',
        'products/2025-04/7266574605.webp',
        'products/2025-04/7320936551.webp',
        'products/2025-05/7433832542.webp',
        'products/2025-05/7490796926.webp',
    ];

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'slug' => fake()->slug(),
            'price' => fake()->randomFloat(2, 10, 30),
            'image' => $this->images[rand(0, count($this->images) - 1)],
        ];
    }
}
