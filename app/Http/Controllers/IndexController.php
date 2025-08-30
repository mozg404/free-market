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
            ->latest()
            ->take(12)
            ->get();

        return Inertia::render('IndexPage', [
            'products' => ProductForListingData::collect($products)
        ]);
    }
}
