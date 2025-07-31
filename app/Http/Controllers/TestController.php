<?php

namespace App\Http\Controllers;

use App\Services\Cart\CartManager;
use App\Services\OrderManager;
use App\Services\PaymentManager;
use App\Services\StockManager;
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
//        $this->payments->topUpBalance(Auth::user(), 1000);

//        dd(Auth::user()->balance);

        return 'Тест';
    }
}
