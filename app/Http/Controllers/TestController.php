<?php

namespace App\Http\Controllers;

use App\Data\UserRegisteringData;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use App\Support\Inn;
use App\Support\Phone;
use function Amp\Dns\query;

class TestController extends Controller
{
    public function test()
    {
        return 'Тест';
    }
}
