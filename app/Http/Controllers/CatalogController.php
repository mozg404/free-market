<?php

namespace App\Http\Controllers;

use App\Data\Feedback\FeedbackData;
use App\Data\Products\ProductDetailedData;
use App\Data\Products\ProductForListingData;
use App\Http\Requests\Catalog\CatalogCategoryFilterableRequest;
use App\Http\Requests\Catalog\CatalogFilterableRequest;
use App\Models\Category;
use App\Models\Feature;
use App\Models\Product;
use App\Support\SeoBuilder;
use Inertia\Inertia;
use Inertia\Response;

class CatalogController extends Controller
{
    public function index(CatalogFilterableRequest $request): Response
    {
        $categories = Category::query()->get()->toTree();
        $products = Product::query()
            ->forListing()
            ->withAvailableStockItemsCount()
            ->filterFromArray($request->getValues())
            ->paginate(20)
            ->appends($request->getValues());

        return Inertia::render('catalog/CatalogPage', [
            'filtersValues' => $request->getValues(),
            'categories' => $categories,
            'products' => ProductForListingData::collect($products),
            'seo' => new SeoBuilder('Каталог товаров'),
        ]);
    }

    public function category(Category $category, CatalogCategoryFilterableRequest $request): Response
    {
        $categories = Category::query()->get()->toTree();
        $features = Feature::query()->forCategoryAndAncestors($category)->get();
        $products = Product::query()
            ->forListing()
            ->withAvailableStockItemsCount()
            ->forCategoryAndDescendants($category)
            ->filterFromArray($request->getValues())
            ->paginate(20)
            ->appends($request->getValues());

        return Inertia::render('catalog/CatalogCategoryPage', [
            'category' => $category,
            'features' => $features,
            'filtersValues' => $request->getValues(),
            'categories' => $categories,
            'products' => ProductForListingData::collect($products),
            'seo' => new SeoBuilder($category),
        ]);
    }

    public function show(Product $product)
    {
        if ($product->isDraft() && (!auth()->check() || auth()->id() !== $product->user_id)) {
            abort(403);
        }

        $feedbacks = $product
            ->feedbacks()
            ->hasComments()
            ->withUser()
            ->latest()
            ->get();

        return Inertia::render('catalog/CatalogProductShowPage', [
            'product' => ProductDetailedData::from($product),
            'feedbacks' => FeedbackData::collect($feedbacks),
            'isOwner' => auth()?->id() === $product->user_id,
            'seo' => new SeoBuilder($product),
        ]);
    }
}
