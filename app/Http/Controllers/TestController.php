<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class TestController extends Controller
{

    public function __construct()
    {}

    public function test(Request $request)
    {
        return 123;
    }


    public function testPage()
    {
        return Inertia::render('test/TestPage');
    }
}
