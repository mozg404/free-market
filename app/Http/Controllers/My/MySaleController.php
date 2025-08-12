<?php

namespace App\Http\Controllers\My;

use App\Data\Products\ProductSoldData;
use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MySaleController extends Controller
{
    public function index()
    {
        $items = OrderItem::query()
            ->withOrder()
            ->withStockItem()
            ->withProduct()
            ->withOrderUser()
            ->isPaid()
            ->forProductUser(auth()->user())
            ->paginate(10);

        return Inertia::render('my/sales/SaleIndexPage', [
            'products' => ProductSoldData::collect($items),
        ]);
    }
}
