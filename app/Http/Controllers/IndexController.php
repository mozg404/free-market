<?php

namespace App\Http\Controllers;

use App\Data\Products\ProductForListingData;
use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class IndexController extends Controller
{
    public function __invoke(): Response
    {
        $products = Product::query()
            ->forListing()
            ->withAvailableStockItemsCount()
            ->latest()
            ->take(10)
            ->get();

        return Inertia::render('IndexPage', [
            'products' => ProductForListingData::collect($products)
        ]);
    }
}
