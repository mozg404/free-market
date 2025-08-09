<?php

namespace App\Http\Controllers;

use App\Data\FeatureData;
use App\Data\Products\ProductDetailedData;
use App\Data\Products\ProductData;
use App\Http\Requests\CatalogRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\Cart\CartManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CatalogController extends Controller
{
    public function index(CatalogRequest $request)
    {
        $filters = $request->all();
        $categories = Category::query()->get();
        $products = Product::query()
            ->filterFromArray($filters)
            ->withAvailableStockItemsCount()
            ->hasAvailableStockItems()
            ->descOrder()
            ->paginate(5);

        return Inertia::render('catalog/Catalog', [
            'filters' => $filters,
            'categories' => $categories,
            'productsPaginate' => ProductData::collect($products)
        ]);
    }

    public function category(Category $category, CatalogRequest $request)
    {
        $filters = $request->all();
        $categories = Category::query()->get();
        $products = Product::query()
            ->filterFromArray($filters)
            ->withAvailableStockItemsCount()
            ->hasAvailableStockItems()
            ->descOrder()
            ->for($category)
            ->get();

        return Inertia::render('catalog/Catalog', [
            'isCategory' => true,
            'category' => $category,
            'features' => $category->features,
            'filters' => $filters,
            'categories' => $categories,
            'products' => ProductData::collect($products)
        ]);
    }

    public function show(Product $product)
    {
        return Inertia::render('ProductShow', [
            'product' => ProductDetailedData::from($product),
        ]);
    }
}
