<?php

namespace App\Http\Controllers\My;

use App\Data\Products\ProductPurchasedData;
use App\Data\Products\ProductPurchasedDetailedData;
use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\StockItem;
use App\Support\SeoBuilder;
use Inertia\Inertia;
use Inertia\Response;

class MyPurchaseController extends Controller
{
    public function index(): Response
    {
        $purchasedProducts = OrderItem::query()
            ->withOrder()
            ->withStockItem()
            ->withProduct()
            ->withSeller()
            ->withProductCategory()
            ->withFeedback()
            ->isPaid()
            ->whereBuyer(auth()->user())
            ->paginate(10);

        return Inertia::render('my/purchases/PurchasedIndexPage', [
            'purchasedProducts' => ProductPurchasedData::collect($purchasedProducts),
            'seo' => new SeoBuilder('Мои покупки'),
        ]);
    }

    public function show(StockItem $stockItem): Response
    {
        return Inertia::render('my/purchases/PurchasedProductShowModal', [
            'purchasedProduct' => ProductPurchasedDetailedData::from($stockItem),
        ]);
    }
}
