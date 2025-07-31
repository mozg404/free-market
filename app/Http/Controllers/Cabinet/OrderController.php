<?php

namespace App\Http\Controllers\Cabinet;

use App\Data\OrderViewData;
use App\Data\PurchasedProductViewData;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index()
    {
        $items = Order::query()
            ->whereUser(Auth::id())
            ->withItemsCount()
            ->descOrder()
            ->get();

        return Inertia::render('cabinet/OrderList', [
            'items' => OrderViewData::collect($items),
        ]);
    }
}
