<?php

namespace App\Http\Controllers\My;

use App\Data\Orders\OrderForListingData;
use App\Data\Orders\OrderItemForListingData;
use App\Exceptions\Balance\InsufficientFundsException;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\Order\OrderProcessor;
use App\Services\PaymentGateway\PaymentService;
use App\Services\Toaster;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class MyOrderController extends Controller
{
    public function __construct(
        private readonly Toaster $toaster,
    ) {
    }

    public function index(): Response
    {
        $orders = Order::query()
            ->whereUser(Auth::id())
            ->withItemsCount()
            ->descOrder()
            ->paginate(10);

        return Inertia::render('my/orders/OrderIndexPage', [
            'orders' => OrderForListingData::collect($orders),
        ]);
    }

    public function show(Order $order): Response
    {
        // Получаем список всех позиций заказа
        $items = OrderItem::query()
            ->for($order)
            ->withProduct()
            ->withProductUser()
            ->get();

        return Inertia::render('my/orders/OrderShowPage', [
            'order' => OrderForListingData::from($order),
            'items' => OrderItemForListingData::collect($items),
            'totalAmount' => $items->getTotalAmount(),
            'totalCount' => $items->count(),
        ]);
    }

    public function pay(
        Order $order,
        OrderProcessor $processor,
        PaymentService $paymentService,
    ) {
        try {
            $processor->process($order);
            $this->toaster->success('Заказ успешно оплачен');

            return redirect()->route('my.orders.show', $order->id);
        } catch (InsufficientFundsException $e) {
            return redirect($paymentService->getPaymentUrl(
                $paymentService->createForOrder($order)
            ));
        }
    }
}
