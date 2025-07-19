<?php

declare(strict_types=1);

namespace Services\Auth;

use App\Data\UserRegisteringData;
use App\Services\Auth\UserRegistrar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRegistrarTest extends TestCase
{
    use RefreshDatabase;

    public function testUniqueEmail()
    {
        $registrar = new UserRegistrar();
        $registrar->register(UserRegisteringData::validateAndCreate([
            'name' =>  'Тест',
            'email' => 'test2@gmail.com',
            'password' => '123456',
        ]));
        $registrar->register(UserRegisteringData::validateAndCreate([
            'name' =>  'Тест',
            'email' => 'test2@gmail.com',
            'password' => '123456',
        ]));
    }
}
