<?php

namespace App\Http\Controllers\My;

use App\Data\Products\ProductPurchasedData;
use App\Data\Products\ProductPurchasedDetailedData;
use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\StockItem;
use Inertia\Inertia;

class MyPurchaseController extends Controller
{
    public function index()
    {
        $purchasedProducts = OrderItem::query()
            ->withOrder()
            ->withStockItem()
            ->withProduct()
            ->withProductUser()
            ->withProductCategory()
            ->isPaid()
            ->forUser(auth()->user())
            ->paginate(10);

        return Inertia::render('my/purchases/PurchasedIndexPage', [
            'purchasedProducts' => ProductPurchasedData::collect($purchasedProducts),
        ]);
    }

    public function show(StockItem $stockItem)
    {
        return Inertia::render('my/purchases/PurchasedProductShowModal', [
            'purchasedProduct' => ProductPurchasedDetailedData::from($stockItem),
        ]);
    }
}
