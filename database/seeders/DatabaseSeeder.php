<?php

namespace Database\Seeders;

use App\Enum\FeatureType;
use App\Models\Category;
use App\Models\Feature;
use App\Models\Product;
use App\Models\StockItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Random\RandomException;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * @throws RandomException
     */
    public function run(): void
    {
        // Создаем главного пользователя
        $mainUser = User::factory()
            ->withAvatar()
            ->create(['email' => 'user@gmail.com']);

        // Создаем рандомных 5 пользователей
        $users = User::factory(5)
            ->withRandomAvatar()
            ->create();

        // Создаем 3 категории
        $categories = Category::factory(5)
            ->has(Feature::factory(4), 'features')
            ->create();

        // Для каждой категории создаем 10 товаров
        $categories->each(function ($category) use ($mainUser, $users) {
            // 5 товаров от главного пользователя
            $products = Product::factory(5)
                ->withImage()
                ->for($category)
                ->for($mainUser)
                ->create();

            // 10 товаров от рандомных пользователей
            $products = $products->merge(
                Product::factory(20)
                    ->withImage()
                    ->for($category)
                    ->for($users->random())
                    ->create()
            );

            // Используем существующую связь features()
            $products->each(function ($product) use ($category) {
                $attachments = [];

                foreach ($category->features as $feature) {
                    $attachments[$feature->id] = $this->generateFeatureValue($feature);
                }

                $product->featuresAttachFrom($attachments);
            });

            // Создаем позиции
            $products->each(fn($p) => StockItem::factory(random_int(3,5))->for($p)->create());
        });
    }

    protected function generateFeatureValue(Feature $feature)
    {
        return match($feature->type) {
            FeatureType::TEXT => fake()->word(),
            FeatureType::NUMBER => fake()->randomNumber(2),
            FeatureType::SELECT => fake()->randomElement($feature->options),
            FeatureType::CHECK => fake()->boolean(),
            default => 'DEFAULT',
        };
    }
}
