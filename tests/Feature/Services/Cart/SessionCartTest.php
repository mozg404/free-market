<?php

declare(strict_types=1);

namespace Services\Cart;

use App\Models\Product;
use App\Services\Cart\SessionCart;
use Illuminate\Foundation\Testing\Concerns\InteractsWithSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class SessionCartTest extends TestCase
{
    use InteractsWithSession;

    private SessionCart $cart;

    protected function setUp(): void
    {
        parent::setUp();

        // Создаем экземпляр корзины (сессия будет инжектирована автоматически)
        $this->cart = app(SessionCart::class);
    }

    public function testAdd()
    {
        $product = Product::factory()->make(['price_base' => 100]);

        // Вызываем метод добавления
        $this->cart->add($product);

        $this->assertSessionHas(SessionCart::SESSION_KEY, [
            $product->id => [
                'product_id' => $product->id,
                'quantity' => 1,
            ],
        ]);

    }
}
