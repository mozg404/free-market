<?php

namespace App\Http\Controllers;

use App\Data\Products\ProductForListingData;
use App\Models\Category;
use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class IndexController extends Controller
{
    public function __invoke(): Response
    {
        $discounted = Product::query()
            ->forListingPreset()
            ->latest()
            ->isDiscounted()
            ->take(12)
            ->get();
        $games = Product::query()
            ->forListingPreset()
            ->latest()
            ->whereCategories(Category::query()->getDescendantsAndSelfIdsByFullPath('keys/games'))
            ->take(12)
            ->get();
        $certificates = Product::query()
            ->forListingPreset()
            ->latest()
            ->whereCategories(Category::query()->getDescendantsAndSelfIdsByFullPath('certificates'))
            ->take(12)
            ->get();
        $subscriptions = Product::query()
            ->forListingPreset()
            ->latest()
            ->whereCategories(Category::query()->getDescendantsAndSelfIdsByFullPath('subscriptions'))
            ->take(12)
            ->get();

        return Inertia::render('IndexPage', [
            'discounted' => ProductForListingData::collect($discounted),
            'games' => ProductForListingData::collect($games),
            'certificates' => ProductForListingData::collect($certificates),
            'subscriptions' => ProductForListingData::collect($subscriptions),
        ]);
    }
}
