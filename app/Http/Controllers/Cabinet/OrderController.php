<?php

namespace App\Http\Controllers\Cabinet;

use App\Data\Orders\OrderData;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::query()
            ->whereUser(Auth::id())
            ->withProductSellers()
            ->withItemsCount()
            ->withItems()
            ->withProducts()
            ->descOrder()
            ->get();

        return Inertia::render('cabinet/OrderList', [
            'orders' => OrderData::collect($orders),
        ]);
    }
}
