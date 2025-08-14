<?php

namespace Tests\Feature\Services\Product;

use App\Exceptions\Product\NotEnoughStockException;
use App\Models\Product;
use App\Models\StockItem;
use App\Services\Product\StockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockServiceTest extends TestCase
{
    use RefreshDatabase;

    private StockService $stock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stock = app(StockService::class);
    }

    public function testHasEnoughStock(): void
    {
        $product = Product::factory()->create();
        StockItem::factory(2)->for($product)->available()->create();
        StockItem::factory()->for($product)->reserved()->create();

        $this->assertTrue($this->stock->hasEnoughStock($product, 2), 'Enough stock should be available');
        $this->assertFalse($this->stock->hasEnoughStock($product, 3), 'Not enough stock');
    }

    public function testEnsureEnoughStock(): void
    {
        $product = Product::factory()->create();
        StockItem::factory(2)->for($product)->available()->create();
        StockItem::factory()->for($product)->reserved()->create();

        $this->expectException(NotEnoughStockException::class);

        $this->stock->ensureStockAvailable($product, 3);
    }

    public function testGetAvailableStockCount()
    {
        $product = Product::factory()->create();
        StockItem::factory()->for($product)->available()->create();
        StockItem::factory()->for($product)->reserved()->create();
        StockItem::factory()->for($product)->available()->create();

        $this->assertEquals(2, $this->stock->getAvailableCount($product));
    }
}
