<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\Image;
use App\Support\Price;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TestController extends Controller
{

    public function __construct()
    {}

    public function test(Request $request)
    {
        $product = new Product();
        $product->name = 'test';
        $product->price = new Price(100);
        $product->description = 'test';
        $product->save();

        return $product;
    }

    public function testPage()
    {
        return Inertia::render('TestPage');
    }

    public function testPage2()
    {
        return Inertia::render('TestPage2');
    }
}
