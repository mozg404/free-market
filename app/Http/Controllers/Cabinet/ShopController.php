<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ShopController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $shops = Shop::query()->where('user_id', $user->id)->get();

        return Inertia::render('cabinet/shops/ShopList', [
            'shops' => $shops
        ]);
    }

    public function create()
    {
        return Inertia::render('cabinet/shops/ShopCreate');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'inn' => ['required', 'string', 'min:10', 'max:10'],
            'address' => ['nullable', 'min:5', 'max:255'],
            'description' => ['nullable', 'max:255'],
        ]);

        $shop = new Shop();
        $shop->user_id = Auth::user()->id;
        $shop->name = $data['name'];
        $shop->slug = Str::slug($data['name']);
        $shop->description = $data['description'] ?? '';
        $shop->inn = $data['inn'];

        $shop->save();

        return to_route('cabinet.shops');
    }

    public function show(Shop $shop)
    {
        return Inertia::render('cabinet/shops/ShopShow', [
            'shop' => $shop,
        ]);
    }

    public function edit(Shop $shop)
    {
        return Inertia::render('cabinet/shops/ShopUpdate', [
            'id' => $shop->id,
            'name' => $shop->name,
            'inn' => $shop->inn,
            'address' => $shop->address,
            'description' => $shop->description,
            'phone' => $shop->phone,
        ]);
    }

    public function update(Shop $shop, Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'inn' => ['required', 'string', 'min:10', 'max:10'],
            'address' => ['nullable', 'min:5', 'max:255'],
            'description' => ['nullable', 'max:255'],
        ]);

        $shop->name = $data['name'];
        $shop->slug = Str::slug($data['name']);
        $shop->address = $data['address'] ?? '';
        $shop->description = $data['description'] ?? '';
        $shop->inn = $data['inn'] ?? '';
        $shop->save();

        return to_route('cabinet.shops');
    }

    public function destroy(string $id)
    {
        //
    }
}
