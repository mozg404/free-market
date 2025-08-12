<?php

namespace App\Http\Controllers;

use App\Data\Products\ProductDetailedData;
use App\Data\Products\ProductForListingData;
use App\Http\Requests\CatalogRequest;
use App\Models\Category;
use App\Models\Product;
use Inertia\Inertia;

class CatalogController extends Controller
{
    public function index(CatalogRequest $request)
    {
        $filters = $request->all();
        $categories = Category::query()->get();
        $products = Product::query()
            ->filterFromArray($filters)
            ->forListing()
            ->withAvailableStockItemsCount()
            ->descOrder()
            ->paginate(20);

        return Inertia::render('catalog/CatalogPage', [
            'filters' => $filters,
            'categories' => $categories,
            'products' => ProductForListingData::collect($products)
        ]);
    }

    public function category(Category $category, CatalogRequest $request)
    {
        $filters = $request->all();
        $categories = Category::query()->get();
        $products = Product::query()
            ->filterFromArray($filters)
            ->forListing()
            ->withAvailableStockItemsCount()
            ->descOrder()
            ->for($category)
            ->paginate(20);

        return Inertia::render('catalog/CatalogPage', [
            'isCategory' => true,
            'category' => $category,
            'features' => $category->features,
            'filters' => $filters,
            'categories' => $categories,
            'products' => ProductForListingData::collect($products)
        ]);
    }

    public function show(Product $product)
    {
        abort_if($product->isDraft(), 404);

        return Inertia::render('catalog/CatalogProductShowPage', [
            'product' => ProductDetailedData::from($product),
        ]);
    }
}
