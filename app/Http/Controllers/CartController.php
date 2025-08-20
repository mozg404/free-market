<?php

namespace App\Http\Controllers;

use App\Exceptions\Product\NotEnoughStockException;
use App\Models\Product;
use App\Services\Cart\CartService;
use App\Services\Toaster;
use App\Support\SeoBuilder;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cart,
        private readonly Toaster $toaster,
    )
    {}

    public function index(): Response
    {
        $props = ['seo' => new SeoBuilder('Корзина')];

        if ($this->cart->isEmpty()) {
            return Inertia::render('cart/EmptyCartIndexPage', $props);
        }

        return Inertia::render('cart/CartIndexPage', $props);
    }

    public function store(Product $product): RedirectResponse
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

    public function decrease(Product $product): RedirectResponse
    {
        $this->cart->remove($product);
        $this->toaster->info('Позиция убрана из корзины');

        return back();
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->cart->removeItem($product);
        $this->toaster->info('Товар удален из корзины');

        return back();
    }

    public function clear(): RedirectResponse
    {
        $this->cart->clear();

        return back();
    }
}
