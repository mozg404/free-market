<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Services\User\UserAvatarChanger;
use App\Services\User\UserRegistrar;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TestController extends Controller
{

    public function __construct()
    {}

    public function test(
        Request $request,
        UserRegistrar $registrar,
        UserAvatarChanger $avatarChanger,
    ): mixed {







        return  123;
    }


    public function testPage(): mixed
    {
        return Inertia::render('test/TestPage', [
            'categoriesTree' => Category::query()->withDepth()->get()->toTree(),
        ]);
    }
}
