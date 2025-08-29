<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TestController extends Controller
{

    public function __construct()
    {}

    public function test(Request $request): mixed
    {

        $user = User::factory()->withRandomAvatar()->create();
        return $user->avatar;

    }


    public function testPage(): mixed
    {
        return Inertia::render('test/TestPage', [
            'categoriesTree' => Category::query()->withDepth()->get()->toTree(),
        ]);
    }
}
