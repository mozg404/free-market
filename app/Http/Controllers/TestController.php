<?php

namespace App\Http\Controllers;

use App\Services\Cart\CartManager;
use App\Services\OrderManager;
use App\Services\PaymentManager;
use App\Services\StockManager;
use App\Support\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{

    public function __construct(
        private PaymentManager $payments,
    )
    {
    }

    public function test(Request $request)
    {
//        $price1 = new Price(900);
//        $price2 = new Price(50, 100);
//        $price3 = $price1->sumWith($price2);
//        dd($price1, $price2, $price3->toArray());


        dd(new Price(1000, 900)->toArray());


        return 'Тест';
    }
}
