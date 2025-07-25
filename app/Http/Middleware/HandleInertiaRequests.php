<?php

namespace App\Http\Middleware;

use App\Services\Cart\CartManager;
use App\Services\Toaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    public function __construct(
        private readonly CartManager $cart,
        private readonly Toaster $toaster,
    )
    {}

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'isAuth' => Auth::check(),
            'user' => [
                'id' => Auth::id(),
                'name' => Auth::user()?->name,
            ],
            'cart' => $this->cart->all(),
            'toasts' => $this->toaster->pull(),
        ];
    }
}
