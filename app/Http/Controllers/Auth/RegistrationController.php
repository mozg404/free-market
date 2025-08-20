<?php

namespace App\Http\Controllers\Auth;

use App\Data\UserRegisteringData;
use App\Http\Controllers\Controller;
use App\Services\Auth\UserRegistrar;
use App\Support\SeoBuilder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class RegistrationController extends Controller
{
    public function __construct(
        private readonly UserRegistrar $registrar
    ) {
    }

    public function show(): Response
    {
        return Inertia::render('Auth/Registration', [
            'seo' => new SeoBuilder('Регистрация'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->registrar->register(UserRegisteringData::validateAndCreate([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ]));

        return redirect()->intended();
    }
}
