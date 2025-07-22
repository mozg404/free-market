<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderManager;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function __construct(
        private OrderManager $orders,
    )
    {}

    /**
     * Страница оформления заказа
     */
    public function index()
    {
        return Inertia::render('payment/Checkout');
    }

    /**
     * Создание заказа
     */
    public function store(Request $request)
    {
//        $order = $this->orders->create();
//        $order = Order::first();
//        dd($order->toArray());

        return 'Оплата';
    }
}
