<?php

namespace App\Http\Controllers;

use App\Exceptions\Product\NotEnoughStockException;
use App\Models\Product;
use App\Services\Cart\CartService;
use App\Services\Toaster;
use Inertia\Inertia;

class CartController extends Controller
{
    public function __construct(
        private CartService $cart,
        private Toaster $toaster,
    )
    {}

    public function index()
    {
        if ($this->cart->isEmpty()) {
            return Inertia::render('cart/EmptyCartIndexPage');
        }

        return Inertia::render('cart/CartIndexPage');
    }

    public function store(Product $product)
    {
        try {
            $this->cart->add($product);
            $this->toaster->success('Добавлено в корзину');

            return back();
        } catch (NotEnoughStockException $e) {
            $this->toaster->error($e->getMessage());

            return back();
        }
    }

    public function decrease(Product $product)
    {
        $this->cart->remove($product);
        $this->toaster->info('Позиция убрана из корзины');

        return back();
    }

    public function destroy(Product $product)
    {
        $this->cart->removeItem($product);
        $this->toaster->info('Товар удален из корзины');

        return back();
    }

    public function clear()
    {
        $this->cart->clear();

        return back();
    }
}
