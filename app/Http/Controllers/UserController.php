<?php

namespace App\Http\Controllers;

use App\Data\Products\ProductForListingData;
use App\Data\User\UserForListingData;
use App\Data\User\UserShortData;
use App\Models\Product;
use App\Models\User;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->withAvailableProductsCount()
            ->hasAvailableProducts()
            ->paginate(20);

        return Inertia::render('users/UsersIndexPage', [
            'users' => UserForListingData::collect($users),
        ]);
    }

    public function show(User $user)
    {
        $products = Product::query()
            ->forListing()
            ->for($user)
            ->latest()
            ->paginate(20);

        return Inertia::render('users/UsersShowPage', [
            'products' => ProductForListingData::collect($products),
            'concreateUser' => UserShortData::from($user),
        ]);
    }
}
