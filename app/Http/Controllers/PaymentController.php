<?php

namespace App\Http\Controllers;

use App\Enum\PaymentSource;
use App\Models\Order;
use App\Models\Payment;
use App\Services\Billing\BillingService;
use App\Services\PaymentManager;
use App\Services\Toaster;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        private readonly BillingService $billing,
        private readonly Toaster $toaster,
    )
    {}

    public function success(Request $request)
    {
        // Ищем платеж по хэшу
        $payment = Payment::findByExternalId($request->input('hash', ''));

        if (!isset($payment)) {
            abort(404);
        }

        // Проверка на то, что платеж уже обработан
        throw_if(!$payment->isPending(), new \ErrorException('Payment is already processed'));

        // Пополняем баланс на сумму платежа
        $this->billing->processPayment($payment);

        // Если ручное пополнение счета - редирект на страницу баланса
        if ($payment->isReplenishmentSource()) {
            $this->toaster->success('Счет успешно пополнен');

            return redirect()->route('balance');
        }

        // Если пополнение в процессе оплаты заказа
        if ($payment->isOrderSource()) {
            $order = Order::find($payment->source_id);
            $this->billing->processOrderPayment($order);
            $this->toaster->success('Заказ успешно оплачен');

            return redirect()->route('cabinet.orders');
        }

        return 'Беда';
    }

    public function fail(string $hash)
    {
        // Ищем платеж по хэшу

        // Меняем статус на отменен

        // Переадрессовываем

        return 'Ошибка платежа';
    }
}
