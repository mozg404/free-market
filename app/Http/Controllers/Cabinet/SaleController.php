<?php

namespace App\Http\Controllers\Cabinet;

use App\Data\PurchasedProductViewData;
use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SaleController extends Controller
{
    public function index()
    {
        $items = OrderItem::query()
            ->withOrder()
            ->withStockItem()
            ->withProduct()
            ->isPaid()
            ->whereSeller(Auth::id())
            ->descOrder()
            ->get();

//        return $purchasedProducts;
//        return PurchasedProductViewData::collect($purchasedProducts);

        return Inertia::render('cabinet/SoldProductList', [
            'items' => PurchasedProductViewData::collect($items),
        ]);
    }
}
