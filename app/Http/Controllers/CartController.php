<?php

namespace App\Http\Controllers;

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

    public function add(Product $product)
    {
        $this->cart->add($product);
        $this->toaster->success('Добавлено в корзину');

        return back();
    }

    public function remove(Product $product)
    {
        $this->cart->remove($product);
        $this->toaster->success('Удалено из корзины');

        return back();
    }

    public function delete(Product $product)
    {
        $this->cart->delete($product);
        $this->toaster->success('Удалено из корзины');

        return back();
    }

    public function clean()
    {
        $this->cart->clean();

        return back();
    }
}
