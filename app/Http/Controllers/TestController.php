<?php

namespace App\Http\Controllers;

use App\Data\Products\ProductData;
use App\Models\Product;
use App\Services\Cart\CartManager;
use App\Services\OrderManager;
use App\Support\Filepond\Image;
use Illuminate\Http\Request;

class TestController extends Controller
{

    public function __construct(
        private CartManager $cart,
        private OrderManager $orders,

    )
    {
    }

    public function test(Request $request)
    {
        $products = Product::query()->whereUser(1)->withAvailableItemsCount()->take(10)->get();

        return 123;
//        dd($products->toArray());


//        $order = $this->orders->create();
//        $order->items;
//        dd($order->toArray());

//        $product1 = Product::find(1);
//        $product2 = Product::find(2);
//
//        $this->cart->add($product1);
//        $this->cart->add($product1);
//        $this->cart->add($product1);
//        $this->cart->add($product2);

//        $this->cart->clean();
//        dd($this->cart->totalPrice());

        return 'Тест';
    }
}
