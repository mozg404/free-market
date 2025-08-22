<?php

namespace Database\Seeders;

use App\Enum\FeatureType;
use App\Enum\TransactionType;
use App\Models\Category;
use App\Models\Feature;
use App\Models\Feedback;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StockItem;
use App\Models\User;
use App\Services\Balance\BalanceService;
use App\Services\Product\ProductService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function __construct(
        private ProductService $productService,
    ) {
    }

    public function run(): void
    {
        $mainUser = User::factory()
            ->withPublishedAvatar()
            ->create([
                'email' => 'user@gmail.com',
                'is_admin' => true,
            ]);

        $users = User::factory(15)
            ->withPublishedAvatar()
            ->create();

        $this->recursiveCreateCategory(
            include resource_path('data/demo_categories.php'),
        );

        // Подгружаем получившийся список категорий
        $categories = Category::query()->get();

        // Создам характеристики
        foreach (include resource_path('data/demo_features.php') as $featureData) {
            $feature = Feature::factory()
                ->withOptions($featureData['options'] ?? null)
                ->withType($featureData['type'])
                ->create([
                    'name' => $featureData['name'],
                ]);

            // Привязываем характеристику к категориям
            foreach ($featureData['categories'] as $categorySlug) {
                $category = $categories->where('full_path', $categorySlug)->first();

                if ($category) {
                    $category->features()->attach($feature->id);
                }
            }
        }

        // Еще раз подгружаем категории, уже вместе с характеристиками
        $categories = Category::query()
            ->with('features')
            ->get();

        // Создаем товары
        foreach (include resource_path('data/demo_products.php') as $productData) {
            $category = $categories->where('full_path', $productData['category'])->first();

            $products = new Collection();

            // Товары для основного пользователя
            if (random_int(1, 100) <= 50) {
                $products = $products->merge(Product::factory(random_int(1,2))
                    ->fromDemo($productData)
                    ->isActive()
                    ->for($category)
                    ->for($mainUser)
                    ->create()
                );
            }

            for ($i = 0; $i < 2; $i++) {
                $products = $products->merge(
                    Product::factory(2)
                        ->fromDemo($productData)
                        ->for($category)
                        ->for($users->random())
                        ->isActive()
                        ->create()
                );
            }

            // Используем существующую связь features()
            $products->each(function (Product $product) use ($category) {
                $attachments = [];

                foreach ($category->features as $feature) {
                    $attachments[$feature->id] = $this->generateFeatureValue($feature);
                }

                $this->productService->changeFeatures($product, $attachments);
            });

            // Создаем позиции
            $products->each(function ($product) {
                StockItem::factory(25)
                    ->for($product)
                    ->available()
                    ->create();
            });
        }


        $this->createOrder($mainUser);
        $this->payOrder($mainUser, $this->createOrder($mainUser));
        $this->payOrder($mainUser, $this->createOrder($mainUser));
        $this->payOrder($mainUser, $this->createOrder($mainUser));
        $this->createOrder($mainUser);
        $this->createOrder($mainUser);
        $this->payOrder($mainUser, $this->createOrder($mainUser));
        $this->createOrder($mainUser);
        $this->createOrder($mainUser);
        $this->payOrder($mainUser, $this->createOrder($mainUser));
        $this->payOrder($mainUser, $this->createOrder($mainUser));
        $this->payOrder($mainUser, $this->createOrder($mainUser));

        // Рандомные заказы от других пользователей
        $users->each(function ($user) {
//            if (random_int(1, 100) <= 75) {
                for ($i = 0; $i < 60; $i++) {
                    $order = $this->createOrder($user);

                    // Выполненный заказ с вероятностью 80%
                    if (random_int(1, 100) <= 90) {
                        $this->payOrder($user, $order);
                    }
                }
//            }
        });
    }

    // Рекурсивное создание категорий
    private function recursiveCreateCategory(array $data, ?Category $parent = null): void
    {
        foreach ($data as $fullPath => $categoryData) {
            $category = Category::create([
                'name' => $categoryData['name'],
                'title' => $categoryData['title'] ?? $categoryData['name'],
                'slug' => $categoryData['slug'],
                'full_path' => $fullPath,
                'parent_id' => $parent?->id,
            ]);

            if (isset($categoryData['children'])) {
                $this->recursiveCreateCategory($categoryData['children'], $category);
            }
        }
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
            ->pending()
            ->withStockItems($stock)
            ->create();

        return $order;
    }

    // Оплачивает заказ
    private function payOrder(User $user, Order $order): void
    {
        $balanceService = app(BalanceService::class);
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
            ->each(function (OrderItem $item) use ($balanceService, $user, $order) {
                // Зачисляем на баланс пользователя сумму заказа
                $balanceService->deposit($user, $order->amount, TransactionType::GATEWAY_DEPOSIT);

                // Списываем с баланса пользователя сумму заказа
                $balanceService->withdraw($user, $order->amount, TransactionType::ORDER_PAYMENT, $order);

                // Зачисляем средства на баланс продавца
                $balanceService->deposit($item->stockItem->product->user, $order->amount, TransactionType::SELLER_PAYOUT, $item);

                // 90% на отзыв
                if (random_int(1, 100) <= 90) {

                    // 80% на отзыв с текстом
                    if (random_int(1, 100) <= 80) {
                        Feedback::factory()->for($user)->forOrderItem($item)->withComment()->create();
                    } else {
                        Feedback::factory()->for($user)->forOrderItem($item)->create();
                    }
                }
            });
    }

}
