<?php

namespace App\Http\Controllers;

use App\Data\Products\ProductData;
use App\Models\Product;
use App\Services\Cart\CartManager;
use App\Support\Filepond\Image;
use Illuminate\Http\Request;

class TestController extends Controller
{

    public function __construct(
        private CartManager $cart,
    )
    {
    }

    public function test(Request $request)
    {
//        $product = Product::first();
//        $data = ProductData::from($product);
//        dd($product->toArray(), $data->toArray());

//        $products = Product::query()->take(10)->get();
//        $data = ProductData::collect($products);
//        dd($products->toArray(), $data->toArray());

//
//
//        $image = Image::from('tmp/a527ec7229048f579adc6e99ce5ca4248b92d37c6sIcTeL15v.webp');
//        dd($image->toArray());






        $product1 = Product::find(1);
        $product2 = Product::find(2);

        $this->cart->add($product1);
        $this->cart->add($product1);
        $this->cart->add($product1);
        $this->cart->add($product2);

//        $this->cart->clean();


//        dd($this->cart->totalPrice());

        return 'Тест';
    }
}
