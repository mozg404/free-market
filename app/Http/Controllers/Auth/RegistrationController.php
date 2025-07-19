<?php

namespace App\Http\Controllers\Auth;

use App\Data\UserRegisteringData;
use App\Http\Controllers\Controller;
use App\Services\Auth\UserRegistrar;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RegistrationController extends Controller
{
    public function __construct(
        private readonly UserRegistrar $registrar
    ) {}

    public function show()
    {
        return Inertia::render('Auth/Registration');
    }

    public function store(Request $request)
    {
        $this->registrar->register(UserRegisteringData::validateAndCreate([
            'name' =>  $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ]));

        return to_route('index');
    }
}
