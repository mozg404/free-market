<?php

namespace App\Http\Controllers;

use App\Exceptions\Product\NotEnoughStockException;
use App\Models\Product;
use App\Services\Cart\CartManager;
use App\Services\Toaster;
use Inertia\Inertia;

class CartController extends Controller
{
    public function __construct(
        private CartManager $cart,
        private Toaster $toaster,
    )
    {}

    public function index()
    {
        return Inertia::render('Cart');
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
        $this->cart->delete($product);
        $this->toaster->info('Товар удален из корзины');

        return back();
    }

    public function clean()
    {
        $this->cart->clean();

        return back();
    }
}
