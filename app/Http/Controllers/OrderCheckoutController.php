<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderManager;
use App\Services\Toaster;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderCheckoutController extends Controller
{
    public function __construct(
        private readonly OrderManager $orders,
        private readonly Toaster $toaster,
    )
    {}

    /**
     * Создание заказа и переход на оплату
     */
    public function store(Request $request)
    {
        $order = $this->orders->create();

//        $order = $this->orders->create();
//        $order = Order::first();
//        dd($order->toArray());

        return redirect()->route('order_checkout.payment', $order->id);
    }

    /**
     * Эмуляция оплаты
     */
    public function payment(Order $order, Request $request)
    {
        // Проверка, что платеж соответствует пользователю
        // Проверка, что платеж имеет статус нового

        // Выводим инфу об оплате и 2 кнопки. Позже тут будет вклинен функционал пополнения счета и списания
        return Inertia::render('payment/Payment', [
            'order' => $order
        ]);
    }

    public function pay(Order $order)
    {
        // Выделяем оплату как прошедшую
        $this->orders->pay($order);

        $this->toaster->success('Заказ успешно оплачен');

        // Редирект в кабинет в раздел заказов
        return redirect()->route('profile.show');
    }
}
