<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Billing\BillingService;
use App\Services\Cart\CartManager;
use App\Services\OrderManager;
use App\Services\Toaster;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderCheckoutController extends Controller
{
    public function __construct(
        private readonly OrderManager $orders,
        private readonly CartManager  $cart,
        private readonly BillingService $billing,
        private readonly Toaster $toaster,
    )
    {}

    /**
     * Создание заказа и переход на оплату
     */
    public function store(Request $request)
    {
        $user = \Auth::user();

        // Создаем заказ
        $order = $this->orders->create($user, $this->cart->all());

        // Очищаем корзину
        $this->cart->clean();

        // Если на счету достаточно средств - выполняем оплату
        if ($this->billing->hasEnoughBalanceFor($order)) {
            $this->billing->processOrderPayment($order);
            $this->toaster->success('Заказ успешно оплачен');

            return redirect()->route('cabinet.orders');
        }

        // Если на счету недостаточно средств - создаем внешний платеж
        $externalPayment = $this->billing->createExternalOrderPayment($order);

        return redirect($externalPayment->toPayUrl);
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
