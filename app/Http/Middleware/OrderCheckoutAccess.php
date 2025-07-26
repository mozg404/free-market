<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Проверяет авторизован ли пользователь перед оформлением заказа
 * Если не авторизован - сохраняет в сессию пометку и перенаправляет на авторизацию
 */
class OrderCheckoutAccess
{
    public const SESSION_KEY = 'checkout_process';

    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            session([self::SESSION_KEY => true]);

            return redirect()->route('login');
        }

        return $next($request);
    }
}
