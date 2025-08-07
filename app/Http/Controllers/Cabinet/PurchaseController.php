<?php

namespace App\Http\Controllers\Cabinet;

use App\Data\PurchasedProductViewData;
use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchasedProducts = OrderItem::query()
            ->withOrder()
            ->withStockItem()
            ->withProduct()
            ->isPaid()
            ->forUser(Auth::id())
            ->descOrder()
            ->get();

//        return $orderItems;
//        return PurchasedProductViewData::collect($purchasedProducts);

        return Inertia::render('cabinet/PurchasedProductList', [
            'items' => PurchasedProductViewData::collect($purchasedProducts),
        ]);
    }
}
