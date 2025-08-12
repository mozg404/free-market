<?php

namespace Tests\Feature\Services\Product;

use App\Exceptions\Product\NotEnoughStockException;
use App\Models\Product;
use App\Models\StockItem;
use App\Models\User;
use App\Services\Product\ProductManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductManagerTest extends TestCase
{
    use RefreshDatabase;

    private ProductManager $productManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productManager = $this->app->make(ProductManager::class);
    }

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
            $this->productManager->checkStockAvailable($product, 1)
        );
        $this->assertFalse(
            $this->productManager->checkStockAvailable($product, 2)
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
        $this->productManager->ensureStockAvailable($product, 2);
    }
}
