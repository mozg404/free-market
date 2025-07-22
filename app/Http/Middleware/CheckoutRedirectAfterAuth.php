<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Если пользователь авторизован и в сессии есть ключ CheckoutAccess::SESSION_KEY
 * удаляет ключ из сессии и перенаправляет на продолжение оформления заказа
 */
class CheckoutRedirectAfterAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (auth()->check() && session()?->has(CheckoutAccess::SESSION_KEY)) {
            session()?->forget(CheckoutAccess::SESSION_KEY);

            return redirect(route('checkout'));
        }

        return $response;
    }
}
