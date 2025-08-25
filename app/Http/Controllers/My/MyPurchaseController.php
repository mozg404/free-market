<?php

namespace App\Http\Controllers\My;

use App\Data\Purchased\PurchasedItemContentData;
use App\Data\Purchased\PurchasedItemForListingData;
use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Support\SeoBuilder;
use Inertia\Inertia;
use Inertia\Response;

class MyPurchaseController extends Controller
{
    public function index(): Response
    {
        $purchasedItems = OrderItem::query()
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
            'purchasedItems' => PurchasedItemForListingData::collect($purchasedItems),
            'seo' => new SeoBuilder('Мои покупки'),
        ]);
    }

    public function content(OrderItem $orderItem): Response
    {
        return Inertia::render('my/purchases/PurchasedItemContentModal', [
            'purchasedItem' => PurchasedItemContentData::from($orderItem),
        ]);
    }
}
