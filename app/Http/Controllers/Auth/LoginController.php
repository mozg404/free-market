<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Support\SeoBuilder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class LoginController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('Auth/Login', [
            'seo' => new SeoBuilder('Авторизация'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'min:5', 'max:255'],
            'password' => ['required', 'min:5', 'max:255']
        ]);

        if (Auth::attempt($data)) {
            return redirect()->intended();
        }

        return back()->withErrors(['email' => 'Пользователь не найден']);
    }
}
