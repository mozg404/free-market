<?php

namespace Tests\Feature\Services\Product;

use App\Exceptions\Product\NotEnoughStockException;
use App\Models\Product;
use App\Models\StockItem;
use App\Models\User;
use App\Services\Product\ProductService;
use App\Support\Price;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    private ProductService $productService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productService = $this->app->make(ProductService::class);
    }

    public function testCorrectCreateProduct(): void
    {
        $user = User::factory()->create();
        $name = 'Тестовый товар';
        $price = new Price(100, 50);

        $product = $this->productService->createProduct($user, $name, $price);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertSame($product->user_id, $user->id);
        $this->assertSame($name, $product->name);
        $this->assertSame(50, $product->price->getCurrentPrice());
        $this->assertSame(100, $product->price->getBasePrice());
    }



    // -----------------------------------------------
    // На удаление
    // -----------------------------------------------

    public function testCheckStockAvailable(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()
            ->isActive()
            ->for($user)
            ->create();
        StockItem::factory(1)
            ->for($product)
            ->available()
            ->create();

        $this->assertTrue(
            $this->productService->checkStockAvailable($product, 1)
        );
        $this->assertFalse(
            $this->productService->checkStockAvailable($product, 2)
        );
    }

    public function testEnsureStockAvailable(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()
            ->for($user)
            ->isActive()
            ->create();
        StockItem::factory(1)
            ->for($product)
            ->available()
            ->create();

        $this->expectException(NotEnoughStockException::class);
        $this->productService->ensureStockAvailable($product, 2);
    }
}
