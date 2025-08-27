<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchStoreRequest;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SearchController extends Controller
{
    public function form(Request $request): Response
    {
        return Inertia::render('SearchModal', [
            'searchValue' => $request->input('search'),
        ]);
    }

    public function store(SearchStoreRequest $request): Collection
    {
        return Product::query()
            ->forListing()
            ->searchAndSort($request->input('search'))
            ->take(20)
            ->get();
    }
}
