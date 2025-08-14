<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\My\MyBalanceController;
use App\Http\Controllers\My\MyOrderController;
use App\Http\Controllers\My\SettingsController;
use App\Http\Controllers\My\MyPurchaseController;
use App\Http\Controllers\My\MySaleController;
use App\Http\Controllers\My\MyProductController as CabinetProductController;
use App\Http\Controllers\My\MyProductStockItemsController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\PaymentCallbackController;
use App\Http\Controllers\SandboxController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckoutAccess;
use App\Http\Middleware\CheckoutExpressAccess;
use Illuminate\Support\Facades\Route;

if (config('app.env') === 'local') {
    Route::get('/test', [TestController::class, 'test']);
    Route::get('/test-page', [TestController::class, 'testPage']);
}

// My
Route::middleware('auth')->prefix('/my')->group(function () {
    // Мои товары
    Route::get('/products', [CabinetProductController::class, 'index'])->name('my.products');
    Route::get('/products/create', [CabinetProductController::class, 'create'])->name('my.products.create');
    Route::post('/products', [CabinetProductController::class, 'store'])->name('my.products.store');
    Route::get('/products/{product}', [CabinetProductController::class, 'show'])
        ->can('view', 'product')
        ->name('my.products.show');

    Route::middleware(['can:update,product'])->group(function () {
        Route::get('/products/{product}/edit', [CabinetProductController::class, 'edit'])
            ->name('my.products.edit');

        Route::put('/products/{product}', [CabinetProductController::class, 'update'])
            ->name('my.products.update');

        Route::delete('/products/{product}', [CabinetProductController::class, 'destroy'])->name('my.products.delete');

        Route::patch('/products/{product}/publish', [CabinetProductController::class, 'publish'])
            ->name('my.products.publish');
        Route::patch('/products/{product}/unpublish', [CabinetProductController::class, 'unpublish'])
            ->name('my.products.unpublish');

        Route::patch('/products/{product}/mark-available', [CabinetProductController::class, 'markAsAvailable'])
            ->name('my.products.mark-available');
        Route::patch('/products/{product}/mark-unavailable', [CabinetProductController::class, 'markAsUnavailable'])
            ->name('my.products.mark-unavailable');

        Route::prefix('/products/{product}')->group(function () {
            Route::get('/stock-items/create', [MyProductStockItemsController::class, 'create'])->name('my.products.stock-items.create');
            Route::post('/stock-items', [MyProductStockItemsController::class, 'store'])->name('my.products.stock-items.store');
            Route::get('/stock-items/{stock_item}/edit', [MyProductStockItemsController::class, 'edit'])->name('my.products.stock-items.edit');
            Route::put('/stock-items/{stock_item}', [MyProductStockItemsController::class, 'update'])->name('my.products.stock-items.update');
        });
    });

    // Настройки профиля
    Route::get('/settings', [SettingsController::class, 'index'])->name('my.settings');
    Route::patch('/settings/change-avatar', [SettingsController::class, 'changeAvatar'])->name('my.settings.change-avatar');

    // Мои заказы
    Route::get('/orders/{order}/pay', [MyOrderController::class, 'pay'])
        ->can('pay', 'order')
        ->name('my.orders.pay');
    Route::get('/orders/{order}', [MyOrderController::class, 'show'])
        ->can('view', 'order')
        ->name('my.orders.show');
    Route::get('/orders', [MyOrderController::class, 'index'])->name('my.orders');

    // Мой баланс
    Route::get('/balance', [MyBalanceController::class, 'index'])->name('my.balance');
    Route::post('/balance/deposit', [MyBalanceController::class, 'deposit'])->name('my.balance.deposit');

    // Мои покупки
    Route::get('/purchases', [MyPurchaseController::class, 'index'])->name('my.purchases');
    Route::get('/purchases/{stock_item}', [MyPurchaseController::class, 'show'])->name('my.purchases.show');

    // Мои продажи
    Route::get('/sales', [MySaleController::class, 'index'])->name('my.sales');
});

// Корзина
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/items/{product}', [CartController::class, 'store'])->name('cart.items.store');
Route::post('/cart/items/{product}/decrease', [CartController::class, 'decrease'])->name('cart.items.decrease');
Route::delete('/cart/items/{product}', [CartController::class, 'destroy'])->name('cart.items.destroy');

// Оформление заказа
Route::get('/checkout', [CheckoutController::class, 'cart'])
    ->middleware(CheckoutAccess::class)
    ->name('checkout');
Route::get('/checkout/express/{product}', [CheckoutController::class, 'express'])
    ->middleware(CheckoutExpressAccess::class)
    ->name('checkout.express');


// Аккаунты пользователей
Route::get('/users', [UserController::class, 'index'])->name('users');
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

// Каталог
Route::get('/catalog/product/{product}', [CatalogController::class, 'show'])->name('catalog.product');
Route::get('/catalog/category/{category:slug}', [CatalogController::class, 'category'])->name('catalog.category');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');

// Эмуляция кассы
Route::get('/demo/sandbox/{hash}', [SandboxController::class, 'index'])->name('sandbox');
Route::get('/demo/sandbox/{hash}/failed', [SandboxController::class, 'failed'])->name('sandbox.failed');
Route::get('/demo/sandbox/{hash}/success', [SandboxController::class, 'success'])->name('sandbox.success');
Route::get('/demo/sandbox/{hash}/cancelled', [SandboxController::class, 'cancelled'])->name('sandbox.cancelled');

// Обработка внешнего платежа
Route::get('/payment/callback', PaymentCallbackController::class)->name('payment.callback');

// Авторизация
Route::middleware('guest')->group(function () {
    Route::post('/registration/store', [RegistrationController::class, 'store'])->name('auth.registration.store');
    Route::get('/registration', [RegistrationController::class, 'show'])->name('auth.registration.show');
    Route::post('/login/store', [LoginController::class, 'store'])->name('login.store');
    Route::get('/login', [LoginController::class, 'show'])->name('login');
});
Route::get('/logout', LogoutController::class)
    ->middleware('auth')
    ->name('logout');

Route::get('/', IndexController::class)->name('home');
