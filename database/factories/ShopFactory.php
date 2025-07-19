<?php

namespace Database\Factories;

use App\Models\Shop;
use App\Support\Inn;
use App\Support\Phone;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Number;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shop>
 */
class ShopFactory extends Factory
{
    protected $model = Shop::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'slug' => Str::slug(fake()->company()),
            'address' => fake()->address(),
            'inn' => Inn::random()->number,
            'phone' => Phone::random()->number,
            'description' => fake()->text(255),
        ];
    }
}
