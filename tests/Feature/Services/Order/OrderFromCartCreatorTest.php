<?php

namespace Tests\Feature\Services\Order;

use App\Enum\OrderStatus;
use App\Models\Product;
use App\Models\StockItem;
use App\Models\User;
use App\Services\Cart\CartService;
use App\Services\Order\OrderCreator;
use App\Services\Order\OrderFromCartCreator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;

class OrderFromCartCreatorTest extends TestCase
{
    use RefreshDatabase;

    private OrderFromCartCreator $creator;
    private CartService $cartService;

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake();

        $this->cartService = $this->app->make(CartService::class);
        $this->orderCreator = new OrderFromCartCreator(
            $this->cartService,
            $this->app->make(OrderCreator::class),
        );
    }

    public function testCorrectCreation(): void
    {
        \Event::fake();

        $user = User::factory()->create();
        $product1 = Product::factory()->isActive()->create();
        $product2 = Product::factory()->isActive()->create();
        StockItem::factory()->for($product1)->available()->create();
        StockItem::factory()->for($product1)->available()->create();
        StockItem::factory()->for($product1)->available()->create();
        StockItem::factory()->for($product2)->available()->create();

        $this->cartService->add($product1, 2);
        $this->cartService->add($product2);

        $order = $this->orderCreator->create($user);

        $this->assertEquals($user->id, $order->user_id);

        // Проверяем наличие в БД
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'user_id' => $user->id,
            'status' => OrderStatus::PENDING->value,
            'amount' => $product1->price->getCurrentPrice() * 2 + $product2->price->getCurrentPrice(),
        ]);

        $this->assertEquals(1, StockItem::query()->forProduct($product1)->isAvailable()->count());
        $this->assertEquals(2, StockItem::query()->forProduct($product1)->isReserved()->count());
        $this->assertEquals(1, StockItem::query()->forProduct($product2)->isReserved()->count());
    }
}
