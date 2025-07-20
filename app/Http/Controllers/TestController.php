<?php

namespace App\Http\Controllers;

use App\Data\UserRegisteringData;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use App\Support\Filepond\Image;
use App\Support\Inn;
use App\Support\Phone;
use function Amp\Dns\query;

class TestController extends Controller
{
    public function test()
    {
//        dd(resource_path('demo/products_images/1.webp'));

        $image = Image::createFromPath(resource_path('demo/products_images/1.webp'))->publish();

        dd($image->id);

        return 'Тест';
    }
}
