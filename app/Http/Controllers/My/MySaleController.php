<?php

namespace App\Http\Controllers\My;

use App\Data\Products\ProductSoldData;
use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Support\SeoBuilder;
use Inertia\Inertia;
use Inertia\Response;

class MySaleController extends Controller
{
    public function index(): Response
    {
        $items = OrderItem::query()
            ->withOrder()
            ->withStockItem()
            ->withProduct()
            ->withBuyer()
            ->isCompleted()
            ->whereSeller(auth()->user())
            ->paginate(10);

        return Inertia::render('my/sales/SaleIndexPage', [
            'products' => ProductSoldData::collect($items),
            'seo' => new SeoBuilder('Мои продажи'),
        ]);
    }
}
