<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\Cart\CartManager;
use Inertia\Inertia;

class CartController extends Controller
{
    private CartManager $cart;

    public function __construct(CartManager $cart)
    {
        $this->cart = $cart;
    }

    public function index()
    {
        return Inertia::render('Cart');
//        return Inertia::render('Cart', [
//            'cart' => $this->cart->all(),
//        ]);
    }

    public function add(Product $product)
    {
        $this->cart->add($product);

        return back();
    }

    public function remove(Product $product)
    {
        $this->cart->remove($product);

        return back();
    }

    public function delete(Product $product)
    {
        $this->cart->delete($product);

        return back();
    }

    public function clean()
    {
        $this->cart->clean();

        return back();
    }
}
