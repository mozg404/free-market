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
use Inertia\Inertia;

class TestController extends Controller
{

    public function __construct()
    {}

    public function test(Request $request)
    {
        dd(Product::find(2)->stockItems()->isAvailable()->count());


        return Product::first();
    }

    public function testPage()
    {
        return Inertia::render('TestPage');
    }

    public function testPage2()
    {
        return Inertia::render('TestPage2');
    }
}
