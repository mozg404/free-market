<?php

namespace App\Http\Controllers\My;

use App\Data\Orders\OrderForListingData;
use App\Data\Orders\OrderItemForListingData;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MyOrderController extends Controller
{
    public function index()
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

    public function show(Order $order)
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
}
