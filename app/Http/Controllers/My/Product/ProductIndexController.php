<?php

namespace App\Http\Controllers\My\Product;

use App\Data\Products\ProductForListingData;
use App\Http\Controllers\Controller;
use App\Http\Requests\MyProduct\ProductFilterableRequest;
use App\Models\Product;
use App\Support\SeoBuilder;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ProductIndexController extends Controller
{
    public function index(ProductFilterableRequest $request): Response
    {
        $products = Product::query()
            ->whereSeller(Auth::id())
            ->withStockItemsCount()
            ->withAvailableStockItemsCount()
            ->filterFromArray($request->getFiltersValues())
            ->paginate(10)
            ->appends($request->getFiltersValues());

        return Inertia::render('my/products/ProductIndexPage', [
            'products' => ProductForListingData::collect($products),
            'filters' => $request->getFiltersValues(),
            'seo' => new SeoBuilder('Мои товары'),
        ]);
    }
}
