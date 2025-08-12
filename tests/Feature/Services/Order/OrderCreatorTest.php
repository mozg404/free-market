<?php

namespace Tests\Feature\Services\Order;

use App\Collections\CreatableOrderItemCollection;
use App\Data\Orders\CreatableOrderItemData;
use App\Enum\OrderStatus;
use App\Exceptions\Product\NotEnoughStockException;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockItem;
use App\Models\User;
use App\Services\Order\OrderCreator;
use App\Support\Price;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderCreatorTest extends TestCase
{
    use RefreshDatabase;

    private OrderCreator $orderCreator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderCreator = $this->app->make(OrderCreator::class);
    }

    public function testNotEnoughStock()
    {
        $user = User::factory()->create();
        $product = Product::factory()->for($user)->isActive()->create();
        StockItem::factory(1)
            ->for($product)
            ->available()
            ->create();

        $this->expectException(NotEnoughStockException::class);
        $this->orderCreator->create($user, new CreatableOrderItemCollection([
            new CreatableOrderItemData($product, 2)
        ]));
    }

    public function testSuccessCreating()
    {
        $price = new Price(1000);
        $quantity = 2;
        $user = User::factory()->create();
        $product = Product::factory()->for($user)->isActive()->withPrice($price)->create();
        $stockItems = StockItem::factory(3)
            ->for($product)
            ->available()
            ->create();

        $order = $this->orderCreator->create($user, new CreatableOrderItemCollection([
            new CreatableOrderItemData($product, $quantity)
        ]));

        $this->assertEquals($order->user->id, $user->id);
        $this->assertEquals($price->getCurrentPrice() * $quantity, $order->amount);
        $this->assertEquals(OrderStatus::NEW, $order->status);

        // Проверяем наличие в БД
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'user_id' => $user->id,
            'amount' => $price->getCurrentPrice() * $quantity,
        ]);

        // Проверка на резервацию
        // 1 доступен из 3
        $this->assertEquals(1, StockItem::query()->forProduct($product)->isAvailable()->count());
        // 2 зарезервировано из 3
        $this->assertEquals(2, StockItem::query()->forProduct($product)->isReserved()->count());
    }
}
