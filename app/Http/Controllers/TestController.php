<?php

namespace App\Http\Controllers;

use App\Data\Products\CreatingProductData;
use App\Models\Payment;
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

        dd(CreatingProductData::from($request));

        return 123;
    }
}
