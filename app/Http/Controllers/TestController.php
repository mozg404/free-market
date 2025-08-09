<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\Image;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TestController extends Controller
{

    public function __construct()
    {}

    public function test(Request $request)
    {

//        "id": "tmp/FK8wKTILSG2DyN3Y7l4HfzCc0J2u10eAyJNNVRSm.jpg",
//        "uri": "/storage/tmp/FK8wKTILSG2DyN3Y7l4HfzCc0J2u10eAyJNNVRSm.jpg",
//        "url": "http://localhost:8080/storage/tmp/FK8wKTILSG2DyN3Y7l4HfzCc0J2u10eAyJNNVRSm.jpg",
//        "name": "FK8wKTILSG2DyN3Y7l4HfzCc0J2u10eAyJNNVRSm.jpg",
//        "size": 35437,
//        "type": "image/jpeg"



        $image = Image::from('http://localhost:8080/storage/tmp/FK8wKTILSG2DyN3Y7l4HfzCc0J2u10eAyJNNVRSm.jpg');

        return $image;
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
