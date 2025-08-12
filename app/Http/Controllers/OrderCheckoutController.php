<?php

namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;
use App\Exceptions\Billing\InsufficientFundsException;
use App\Exceptions\Product\NotAvailableForPurchaseException;
use App\Exceptions\Product\NotEnoughStockException;
use App\Services\Order\OrderFromCartCreator;
use App\Services\Order\OrderPaymentProcessor;
use App\Services\Toaster;

class OrderCheckoutController extends Controller
{
    public function __construct(
        private readonly Toaster $toaster,
    )
    {}

    public function store(
        OrderFromCartCreator $creator,
        OrderPaymentProcessor $processor,
        PaymentGateway $gateway,
    ) {
        try {
            $user = auth()->user();
            $order = $creator->create($user);

            try {
                $processor->process($order);
                $this->toaster->success('Заказ успешно оплачен');

                return redirect()->route('my.orders.show', $order->id);
            } catch (InsufficientFundsException $e) {
                return redirect($gateway->getPaymentUrl(
                    $gateway->createForOrder($order)
                ));
            }
        } catch (NotEnoughStockException|NotAvailableForPurchaseException $e) {
            $this->toaster->error($e->getMessage());

            return back();
        } catch (\Throwable $e) {
            report($e);
            $this->toaster->error('Ошибка при оформлении заказа');

            return back();
        }
    }
}
