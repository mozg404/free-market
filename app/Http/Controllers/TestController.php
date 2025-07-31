<?php

namespace App\Http\Controllers;

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

    public function __construct(
        private PaymentManager $payments,
    )
    {
    }

    public function test(Request $request)
    {
        $amount = 1000;

        // Создаем платеж
        $payment = Payment::new(Auth::user(), 1000);

        // Генерируем внешний ID
        $hash = Sandbox::createId($payment->id, $amount);

        // Сохраняем внешний ид в модель
        $payment->toPend($hash);

        // Перенаправляем в

        return $hash;
    }
}
