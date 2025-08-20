<?php

namespace App\Http\Controllers;

use App\Data\Products\ProductDetailedData;
use App\Data\Products\ProductForListingData;
use App\Http\Requests\CatalogRequest;
use App\Models\Category;
use App\Models\Product;
use App\Support\SeoBuilder;
use Inertia\Inertia;
use Inertia\Response;

class CatalogController extends Controller
{
    public function index(CatalogRequest $request): Response
    {
        $filters = $request->all();
        $categories = Category::query()->get()->toTree();
        $products = Product::query()
            ->filterFromArray($filters)
            ->forListing()
            ->withAvailableStockItemsCount()
            ->latest()
            ->paginate(20);

        return Inertia::render('catalog/CatalogPage', [
            'filters' => $filters,
            'categories' => $categories,
            'products' => ProductForListingData::collect($products),
            'seo' => new SeoBuilder('Каталог товаров')
        ]);
    }

    public function category(Category $category, CatalogRequest $request): Response
    {
        $filters = $request->all();
        $categories = Category::query()->get()->toTree();
        $products = Product::query()
            ->filterFromArray($filters)
            ->forListing()
            ->withAvailableStockItemsCount()
            ->latest()
            ->for($category)
            ->paginate(20);

        return Inertia::render('catalog/CatalogPage', [
            'isCategory' => true,
            'category' => $category,
            'features' => $category->features,
            'filters' => $filters,
            'categories' => $categories,
            'products' => ProductForListingData::collect($products),
            'seo' => new SeoBuilder($category),
        ]);
    }

    public function show(Product $product): Response
    {
        if ($product->isDraft() && (!auth()->check() || auth()->id() !== $product->user_id)) {
            abort(403);
        }

        return Inertia::render('catalog/CatalogProductShowPage', [
            'product' => ProductDetailedData::from($product),
            'isOwner' => auth()?->id() === $product->user_id,
            'seo' => new SeoBuilder($product),
        ]);
    }
}
