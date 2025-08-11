<?php

namespace App\Http\Controllers;

use App\Exceptions\Payment\PaymentAlreadyProcessedException;
use App\Models\Payment;
use App\Services\Payment\PaymentProcessor;
use App\Services\Toaster;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        private readonly Toaster $toaster,
    )
    {}

    public function success(Request $request, PaymentProcessor $processor)
    {
        $payment = Payment::findByExternalId($request->input('hash', ''));

        if (!isset($payment)) {
            abort(404);
        }

        try {
            $processor->process($payment);

            if ($payment->isOrderSource()) {
                $this->toaster->success('Заказ успешно оплачен');

                return redirect()->route('my.orders.show', $payment->sourceable->id);
            }
        } catch (PaymentAlreadyProcessedException $e) {
            $this->toaster->success($e->getMessage());

            return redirect()->route('my.balance');
        } catch (\Throwable $e) {
            report($e);
            $this->toaster->error('Ошибка при оплате');

            return back();
        }

        $this->toaster->success('Счет успешно пополнен');

        return redirect()->route('my.balance');
    }

    public function fail(string $hash)
    {
        // Ищем платеж по хэшу

        // Меняем статус на отменен

        // Переадрессовываем

        return 'Ошибка платежа';
    }
}
