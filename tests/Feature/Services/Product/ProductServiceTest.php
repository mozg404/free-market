<?php

namespace Tests\Feature\Services\Product;

use App\Enum\TransactionType;
use App\Exceptions\Product\NotEnoughStockException;
use App\Models\Category;
use App\Models\Feature;
use App\Models\Product;
use App\Models\ProductFeatureValue;
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

    // -----------------------------------------------
    // createProduct(User $user, string $name, Price $price)
    // -----------------------------------------------

    public function testCorrectCreateProduct(): void
    {
        $user = User::factory()->create();
        $name = 'Тестовый товар';
        $price = new Price(100, 50);
        $category = Category::factory()->create();

        $product = $this->productService->createProduct($user, $category, $name, $price);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertSame($product->user_id, $user->id);
        $this->assertSame($name, $product->name);
        $this->assertSame(50, $product->price->getCurrentPrice());
        $this->assertSame(100, $product->price->getBasePrice());
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'user_id' => $user->id,
            'base_price' => $price->getBasePrice(),
            'current_price' => $price->getCurrentPrice(),
        ]);
    }

    // -----------------------------------------------
    // changePrice(Product $product, Price $price)
    // -----------------------------------------------

    public function testCorrectChangePrice(): void
    {
        $product = Product::factory()->withPrice(new Price(1000))->create();
        $newPrice = new Price(931, 450);

        $this->productService->changePrice($product, $newPrice);

        $this->assertNotNull($product->price);
        $this->assertInstanceOf(Price::class, $product->price);
        $this->assertSame($newPrice->getBasePrice(), $product->price->getBasePrice());
        $this->assertSame($newPrice->getDiscountPrice(), $product->price->getDiscountPrice());
        $this->assertDatabaseHas('products', [
            'base_price' => $newPrice->getBasePrice(),
            'current_price' => $newPrice->getCurrentPrice(),
        ]);
    }

    // -----------------------------------------------
    // changeFeatures(Product $product, array $features)
    // -----------------------------------------------

    public function testCorrectChangeFeatures(): void
    {
        $feature1 = Feature::factory()->create();
        $feature2 = Feature::factory()->create();
        $feature3 = Feature::factory()->create();
        $product = Product::factory()->create();
        ProductFeatureValue::factory()->for($feature1)->for($product)->create();
        $feature2Value = ProductFeatureValue::factory()->generateValueFor($feature2);
        $feature3Value = ProductFeatureValue::factory()->generateValueFor($feature3);

        $this->productService->changeFeatures($product, [
            $feature2->id => $feature2Value,
            $feature3->id => $feature3Value,
        ]);

        // Стерлась запись 1
        $this->assertDatabaseMissing(ProductFeatureValue::TABLE, [
            'product_id' => $product->id,
            'feature_id' => $feature1->id,
        ]);
        // Присутствует запись 2
        $this->assertDatabaseHas(ProductFeatureValue::TABLE, [
            'product_id' => $product->id,
            'feature_id' => $feature2->id,
            'value' => $feature2Value,
        ]);
        // Присутствует запись 3
        $this->assertDatabaseHas(ProductFeatureValue::TABLE, [
            'product_id' => $product->id,
            'feature_id' => $feature3->id,
            'value' => $feature3Value,
        ]);
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
