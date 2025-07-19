<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function show()
    {
        return Inertia::render('Auth/Login');
    }

    public function store(Request $request)
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
