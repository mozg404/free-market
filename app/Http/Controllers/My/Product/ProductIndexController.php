<?php

namespace App\Http\Controllers\My\Product;

use App\Data\Products\ProductForListingData;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ProductIndexController extends Controller
{
    public function index(Request $request): Response
    {
        $products = Product::query()
            ->forUser(Auth::id())
            ->withStockItemsCount()
            ->withAvailableStockItemsCount()
            ->orderBy('id', 'desc');

        if (!empty($request->input('search'))) {
            $products->searchByName($request->input('search'));
        }

        $products = $products->paginate(10);

        return Inertia::render('my/products/ProductIndexPage', [
            'products' => ProductForListingData::collect($products->items()),
            'links' => $products->toArray()['links'],
            'filters' => $request->only(['search', 'shop_id']),
        ]);
    }
}
