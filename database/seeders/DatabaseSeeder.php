<?php

namespace Database\Seeders;

use App\Enum\FeatureType;
use App\Models\Category;
use App\Models\Feature;
use App\Models\Product;
use App\Models\StockItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $demoCategoriesData = include resource_path('data/demo_categories.php');
        $demoFeaturesData = include resource_path('data/demo_features.php');
        $demoProductsData = include resource_path('data/demo_products.php');
        $demoData = [];

        // Объединяем демо данные воедино
        foreach ($demoCategoriesData as $demoCategoryKey => $demoCategory) {
            $demoData[$demoCategoryKey] = $demoCategory;

            if (isset($demoFeaturesData[$demoCategoryKey])) {
                $demoData[$demoCategoryKey]['features'] = $demoFeaturesData[$demoCategoryKey];
            }

            if (isset($demoProductsData[$demoCategoryKey])) {
                $demoData[$demoCategoryKey]['products'] = $demoProductsData[$demoCategoryKey];
            }
        }

        // Создаем главного пользователя
        $mainUser = User::factory()
            ->withAvatar()
            ->create(['email' => 'user@gmail.com']);

        // Создаем рандомных 5 пользователей
        $users = User::factory(5)
            ->withAvatar()
            ->create();


        foreach ($demoData as $categoryKey => $categoryData) {
            $category = Category::create([
                'name' => $categoryData['name'],
                'slug' => Str::slug($categoryData['name'])
            ]);

            foreach ($categoryData['features'] ?? [] as $featureData) {
                Feature::factory()
                    ->for($category)
                    ->withOptions($featureData['options'] ?? null)
                    ->withType($featureData['type'])
                    ->create([
                        'name' => $featureData['name'],
                    ]);
            }

            // Дозагружаем характеристики
            $category->load('features');

            if (isset($categoryData['products'])) {
                // Товары для основного пользователя
                $products = Product::factory(random_int(0,10))
                    ->fromDemo($categoryData['products'] ?? [])
                    ->for($category)
                    ->for($mainUser)
                    ->create();

                // 10 товаров от рандомных пользователей
                $products = $products->merge(
                    Product::factory(random_int(0,20))
                        ->fromDemo($categoryData['products'] ?? [])
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
                $products->each(fn($p) => StockItem::factory(random_int(5,20))->for($p)->create());
            }
        }
    }

    protected function generateFeatureValue(Feature $feature)
    {
        return match($feature->type) {
            FeatureType::TEXT => fake()->word(),
            FeatureType::NUMBER => fake()->randomNumber(2),
            FeatureType::SELECT => fake()->randomElement(array_keys($feature->options)),
            FeatureType::CHECK => fake()->boolean(),
            default => 'DEFAULT',
        };
    }
}
