<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TestController extends Controller
{

    public function __construct()
    {}

    public function test(Request $request)
    {
        dd(Product::find(2)->stockItems()->isAvailable()->count());


        return Product::first();
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
