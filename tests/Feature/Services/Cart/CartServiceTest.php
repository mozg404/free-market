<?php

namespace Tests\Feature\Services\Cart;

use App\Contracts\Cart;
use App\Models\Product;
use App\Services\Cart\CartService;
use App\Services\Product\StockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartServiceTest extends TestCase
{
    use RefreshDatabase;

    // --------------------------------------------
    // add
    // --------------------------------------------

    public function testCorrectAdd(): void
    {
        $cartMock = $this->createMock(Cart::class);
        $stockServiceMock = $this->createMock(StockService::class);
        $product = Product::factory()->make(['id' => 123]);

        $cartMock->expects($this->once())
            ->method('add')
            ->with($product->id, 1);
        $stockServiceMock->expects($this->once())
            ->method('ensureStockAvailable')
            ->with($product, 1);
        $cartService = new CartService($cartMock, $stockServiceMock);

        $cartService->add($product);
    }

    public function testAddWithCustomQuantity(): void
    {
        $cartMock = $this->createMock(Cart::class);
        $stockServiceMock = $this->createMock(StockService::class);
        $product = Product::factory()->make(['id' => 123]);
        $quantity = 5;

        $cartMock->expects($this->once())
            ->method('add')
            ->with($product->id, $quantity);
        $stockServiceMock->expects($this->once())
            ->method('ensureStockAvailable')
            ->with($product, $quantity);
        $cartService = new CartService($cartMock, $stockServiceMock);

        $cartService->add($product, $quantity);
    }

    // --------------------------------------------
    // remove
    // --------------------------------------------

    public function testCorrectRemove(): void
    {
        $cartMock = $this->createMock(Cart::class);
        $stockServiceMock = $this->createMock(StockService::class);
        $product = Product::factory()->make(['id' => 123]);

        $cartMock->expects($this->once())
            ->method('remove')
            ->with($product->id, 1);
        $cartService = new CartService($cartMock, $stockServiceMock);

        $cartService->remove($product);
    }

    public function testRemoveWithCustomQuantity(): void
    {
        $cartMock = $this->createMock(Cart::class);
        $stockServiceMock = $this->createMock(StockService::class);
        $product = Product::factory()->make(['id' => 123]);
        $quantity = 5;

        $cartMock->expects($this->once())
            ->method('remove')
            ->with($product->id, $quantity);
        $cartService = new CartService($cartMock, $stockServiceMock);

        $cartService->remove($product, $quantity);
    }

    // --------------------------------------------
    // has
    // --------------------------------------------

    public function testCorrectHas(): void
    {
        $cartMock = $this->createMock(Cart::class);
        $stockServiceMock = $this->createMock(StockService::class);
        $product = Product::factory()->make(['id' => 123]);

        $cartMock->expects($this->once())
            ->method('has')
            ->with($product->id);
        $cartService = new CartService($cartMock, $stockServiceMock);

        $cartService->has($product);
    }

    // --------------------------------------------
    // removeItem
    // --------------------------------------------

    public function testCorrectRemoveItem(): void
    {
        $cartMock = $this->createMock(Cart::class);
        $stockServiceMock = $this->createMock(StockService::class);
        $product = Product::factory()->make(['id' => 123]);

        $cartMock->expects($this->once())
            ->method('removeItem')
            ->with($product->id);
        $cartService = new CartService($cartMock, $stockServiceMock);

        $cartService->removeItem($product);
    }

    // --------------------------------------------
    // clear
    // --------------------------------------------

    public function testClear(): void
    {
        $cartMock = $this->createMock(Cart::class);
        $stockServiceMock = $this->createMock(StockService::class);

        $cartMock->expects($this->once())
            ->method('clear');
        $cartService = new CartService($cartMock, $stockServiceMock);

        $cartService->clear();
    }

    // --------------------------------------------
    // getItem
    // --------------------------------------------

    public function testCorrectGetItem(): void
    {
        $cartMock = $this->createMock(Cart::class);
        $stockServiceMock = $this->createMock(StockService::class);
        $product = Product::factory()->make(['id' => 123]);

        $cartMock->expects($this->once())
            ->method('has')
            ->with($product->id)
            ->willReturn(true);
        $cartMock->expects($this->once())
            ->method('getQuantityFor')
            ->with($product->id)
            ->willReturn(5);

        $cartService = new CartService($cartMock, $stockServiceMock);

        $item = $cartService->getItem($product);

        $this->assertNotNull($item);
        $this->assertEquals($product->id, $item->id);
        $this->assertEquals($product->name, $item->name);
        $this->assertEquals(5, $item->quantity);
    }

    public function testGetNonExistsItem(): void
    {
        $cartMock = $this->createMock(Cart::class);
        $stockServiceMock = $this->createMock(StockService::class);
        $product = Product::factory()->make(['id' => 123]);

        $cartMock->expects($this->once())
            ->method('has')
            ->with($product->id)
            ->willReturn(false);
        $cartService = new CartService($cartMock, $stockServiceMock);

        $item = $cartService->getItem($product);

        $this->assertNull($item);
    }

    // --------------------------------------------
    // isEmpty
    // --------------------------------------------

    public function testCorrectIsEmpty(): void
    {
        $cartMock = $this->createMock(Cart::class);
        $stockServiceMock = $this->createMock(StockService::class);

        $cartMock->expects($this->once())
            ->method('isEmpty');
        $cartService = new CartService($cartMock, $stockServiceMock);

        $cartService->isEmpty();
    }

    // --------------------------------------------
    // getItems
    // --------------------------------------------

    public function testGetItems(): void
    {
        $cartMock = $this->createMock(Cart::class);
        $stockServiceMock = $this->createMock(StockService::class);
        $product = Product::factory()->create(['id' => 123]);
        $quantity = 5;

        $cartMock->expects($this->once())
            ->method('getIds')
            ->willReturn([$product->id]);
        $cartMock->expects($this->once())
            ->method('getQuantityFor')
            ->willReturn($quantity);
        $cartService = new CartService($cartMock, $stockServiceMock);

        $cart = $cartService->getItems();

        $this->assertCount(1, $cart->items);
        $this->assertEquals($product->id, $cart->items[0]->id);
        $this->assertEquals($quantity, $cart->items[0]->quantity);
    }
}
