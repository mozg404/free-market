<?php

namespace Database\Seeders;

use App\Enum\FeatureType;
use App\Enum\TransactionType;
use App\Models\Category;
use App\Models\Feature;
use App\Models\Order;
use App\Models\OrderItem;
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
        $users = User::factory(20)
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
                    Product::factory(random_int(0,15))
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
                $products->each(function ($product) use ($category) {
                    StockItem::factory(random_int(0,10))
                        ->for($product)
                        ->available()
                        ->create();
                });
            }
        }

        // 2 неоплаченных заказа и 3 оплаченных для основного пользователя
        $this->createOrder($mainUser);
        $this->payOrder($mainUser, $this->createOrder($mainUser));
        $this->createOrder($mainUser);
        $this->payOrder($mainUser, $this->createOrder($mainUser));
        $this->payOrder($mainUser, $this->createOrder($mainUser));
        $this->payOrder($mainUser, $this->createOrder($mainUser));

        // Рандомные заказы от других пользователей
        $users->each(function ($user) {
            if (random_int(1, 100) <= 50) {
                for ($i = 0; $i < random_int(1, 3); $i++) {
                    $order = $this->createOrder($user);

                    // Выполненный заказ с вероятностью 80%
                    if (random_int(1, 100) <= 80) {
                        $this->payOrder($user, $order);
                    }
                }
            }
        });
    }

    private function generateFeatureValue(Feature $feature)
    {
        return match($feature->type) {
            FeatureType::TEXT => fake()->word(),
            FeatureType::NUMBER => fake()->randomNumber(2),
            FeatureType::SELECT => fake()->randomElement(array_keys($feature->options)),
            FeatureType::CHECK => fake()->boolean(),
            default => 'DEFAULT',
        };
    }

    // Генерирует новый заказ для пользователя
    private function createOrder(User $user): Order
    {
        // Получаем случайно количество доступных для покупки позиций товара со склада
        $stock = StockItem::query()
            ->inRandomOrder()
            ->whereNotBelongsToUser($user)
            ->canByPurchased()
            ->with('product', function ($query) {
                return $query
                    ->select('id', 'user_id', 'category_id', 'status', 'current_price', 'base_price')
                    ->with('user', fn ($q) => $q->select('id', 'name', 'balance'));
            })
            ->take(random_int(1, 5))
            ->get();

        // Генерируем заказ из позиций на складе
        $order = Order::factory()
            ->for($user)
            ->asNew()
            ->withStockItems($stock)
            ->create();

        return $order;
    }

    // Оплачивает заказ
    public function payOrder(User $user, Order $order): void
    {
        $order->fresh();
        $order->markAsPaid();
        $order
            ->items()
            ->with('stockItem', function ($builder) {
                return $builder
                    ->with(['product' => function ($query) {
                        return $query
                            ->select('id', 'user_id', 'category_id', 'status', 'current_price', 'base_price')
                            ->with('user', fn ($q) => $q->select('id', 'name', 'balance'));
                    }]);
            })
            ->get()
            ->each(function (OrderItem $item) use ($user, $order) {
                // Зачисляем на баланс пользователя сумму заказа
                $user->deposit($order->amount, TransactionType::DEPOSIT);

                // Списываем с баланса пользователя сумму заказа
                $user->withdraw($order->amount, TransactionType::PURCHASE, $order);

                // Помечаем позицию на складе как проданную
                $item->stockItem->markAsSold($user);

                // Зачисляем средства на баланс продавца
                $item->stockItem->product->user->deposit($order->amount, TransactionType::SALE, $item->stockItem);
            });
    }
}
