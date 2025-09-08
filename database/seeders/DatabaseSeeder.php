<?php

namespace Database\Seeders;

use App\Enum\OrderStatus;
use App\Enum\TransactionType;
use App\Jobs\CreateRandomDemoUser;
use App\Jobs\CreateSpecificDemoProduct;
use App\Models\Category;
use App\Models\Feature;
use App\Models\Feedback;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StockItem;
use App\Models\User;
use App\Services\Balance\BalanceService;
use App\Services\Demo\DemoProductCreator;
use App\Services\Demo\DemoProductList;
use App\Services\User\DemoUserCreator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function __construct(
        private readonly DemoUserCreator $userCreator,
        private readonly DemoProductList $productList,
        private readonly DemoProductCreator $productCreator,
    ) {
    }

    public function run(): void
    {
        $this->command->info('Регистрируем пользователей..');
        $mainUser = $this->userCreator->create(
            name: fake()->userName(),
            email: config('demo.main_user_email'),
            password: config('demo.main_user_password'),
            avatarPath: fake()->randomElement(include resource_path('data/user_avatars.php')),
            isAdmin: true
        );
        $users = new Collection();

        for ($i = 0; $i < config('demo.random_users_seed_count'); ++$i) {
            $user = $this->userCreator->create(
                name: fake()->userName(),
                email: fake()->unique()->email(),
                password: config('demo.random_user_password'),
                avatarPath: fake()->randomElement(include resource_path('data/user_avatars.php')),
            );
            $users->push($user);
        }

        $this->command->info('Ставим доп. пользователей для регистрации в очереди..');

        for ($i = 0; $i < config('demo.random_users_seed_queue_count'); ++$i) {
            CreateRandomDemoUser::dispatch();
        }

        $this->command->info('Создаем категории...');
        $this->recursiveCreateCategory(
            include resource_path('data/demo_categories.php'),
        );

        // Подгружаем получившийся список категорий
        $categories = Category::query()->get();

        $this->command->info('Создаем динамические характеристики...');

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

        $this->command->info('Создаем товары...');

        // Товары для основного пользователя
        for ($i = 0; $i < config('demo.products_seed_count_for_main'); ++$i) {
            $this->productCreator->create($mainUser, $this->productList->random());
        }

        // Товары для случайных пользователей
        for ($i = 0; $i < config('demo.products_seed_count'); ++$i) {
            $this->productCreator->create($mainUser, $this->productList->random());
        }

        // Ставим весь список товаров в очередь, каждого по 3 вариации
        foreach ($this->productList->all() as $data) {
            CreateSpecificDemoProduct::dispatch($data);
        }

        echo 'Создание заказов для основного пользователя...' . PHP_EOL;

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

        echo 'Создание заказов для случайных пользователей...' . PHP_EOL;

        // 5 выполненных заказов для пользователя
        $users->each(function ($user) {
            for ($i = 0; $i < 5; $i++) {
                $order = $this->createOrder($user);
                $this->payOrder($user, $order);
            }
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
            ->take(random_int(3, 8))
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
        $order->update([
            'status' => OrderStatus::COMPLETED
        ]);
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

                // 80% на отзыв
                if (random_int(1, 100) <= 80) {

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
