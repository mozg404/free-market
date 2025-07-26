<?php

namespace App\Http\Controllers;

use App\Data\Products\ProductData;
use App\Enum\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\Cart\CartManager;
use App\Services\OrderManager;
use App\Services\StockManager;
use App\Support\Filepond\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{

    public function __construct(
        private CartManager $cart,
        private OrderManager $orders,
        private StockManager $stock,
    )
    {
    }

    public function test(Request $request)
    {
//        $order = $this->orders->create();
//        $order->items;

        $order = Order::find(1);

        $this->orders->pay($order);

        return $order;

//        $products = Product::query()->with('stockItems')->whereUser(1)->take(10)->get();
//        $products = Product::query()->withAvailableStockItemsCount()->whereUser(1)->take(10)->get();
//
//        return $products;
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
