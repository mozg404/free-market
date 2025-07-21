<?php

namespace Database\Factories;

use App\Models\Product;
use App\Support\Filepond\Image;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    private array $images = [
        'demo/products_images/1.webp',
        'demo/products_images/2.webp',
        'demo/products_images/3.webp',
        'demo/products_images/4.webp',
        'demo/products_images/5.webp',
        'demo/products_images/6.webp',
        'demo/products_images/7.webp',
        'demo/products_images/8.webp',
        'demo/products_images/9.webp',
        'demo/products_images/10.webp',
        'demo/products_images/11.webp',
        'demo/products_images/12.webp',
        'demo/products_images/13.webp',
        'demo/products_images/14.webp',
    ];

    public function definition(): array
    {
        $imagePath = resource_path($this->images[rand(0, count($this->images) - 1)]);

        return [
            'name' => fake()->name(),
            'slug' => fake()->slug(),
            'price_base' => random_int(300, 10000),
            'is_available' => fake()->boolean(),
            'preview_image' => Image::createFromPath($imagePath)->id,
        ];
    }
}
