<?php

namespace App\Http\Controllers;

use App\Data\Products\CreatingProductData;
use App\Models\Payment;
use App\Models\Product;
use App\Services\Cart\CartManager;
use App\Services\OrderManager;
use App\Services\PaymentManager;
use App\Services\StockManager;
use App\Support\Price;
use App\Support\Sandbox;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{

    public function __construct()
    {}

    public function test(Request $request)
    {
        $featuresValues = [
            37  => 'item_3',
        ];

        $products = Product::query()
            ->whereFeatureValues($featuresValues)
//            ->withFeatures()
            ->get();


//        return 123;
        return $products->toArray();
    }
}
