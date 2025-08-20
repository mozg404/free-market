<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TestController extends Controller
{

    public function __construct()
    {}

    public function test(Request $request): mixed
    {
        return 123;
    }


    public function testPage(): mixed
    {
        return Inertia::render('test/TestPage', [
            'categoriesTree' => Category::query()->withDepth()->get()->toTree(),
        ]);
    }
}
