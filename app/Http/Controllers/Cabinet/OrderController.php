<?php

namespace App\Http\Controllers\Cabinet;

use App\Data\Orders\OrderForListingData;
use App\Data\Orders\OrderItemForListingData;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::query()
            ->whereUser(Auth::id())
            ->withItemsCount()
            ->descOrder()
            ->paginate(10);

        return Inertia::render('orders/OrderIndex', [
            'pagination' => OrderForListingData::collect($orders),
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

        return Inertia::render('orders/OrderShow', [
            'order' => OrderForListingData::from($order),
            'items' => OrderItemForListingData::collect($items),
            'totalAmount' => $items->getTotalAmount(),
            'totalCount' => $items->count(),
        ]);
    }
}
