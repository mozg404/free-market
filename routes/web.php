<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\My\MyBalanceController;
use App\Http\Controllers\My\MyOrderController;
use App\Http\Controllers\My\SettingsController;
use App\Http\Controllers\My\PurchaseController;
use App\Http\Controllers\My\SaleController;
use App\Http\Controllers\My\MyProductController as CabinetProductController;
use App\Http\Controllers\My\MyProductStockItemsController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\OrderCheckoutController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SandboxController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\OrderCheckoutAccess;
use Illuminate\Support\Facades\Route;

if (config('app.env') === 'local') {
    Route::get('/test', [TestController::class, 'test']);
    Route::get('/test-page', [TestController::class, 'testPage']);
    Route::get('/test-page2', [TestController::class, 'testPage2']);
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
    Route::get('/orders/{order}', [MyOrderController::class, 'show'])
        ->can('view', 'order')
        ->name('my.orders.show');
    Route::get('/orders', [MyOrderController::class, 'index'])->name('my.orders');

    // Мой баланс
    Route::get('/balance', [MyBalanceController::class, 'index'])->name('my.balance');
    Route::post('/balance/deposit', [MyBalanceController::class, 'deposit'])->name('my.balance.deposit');
});

Route::middleware('auth')->group(function () {
    Route::get('/cabinet/purchases', [PurchaseController::class, 'index'])->name('cabinet.purchases');
    Route::get('/cabinet/sales', [SaleController::class, 'index'])->name('cabinet.sales');
});

// Корзина
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::delete('/cart', [CartController::class, 'clean'])->name('cart.clean');
Route::post('/cart/items/{product}', [CartController::class, 'store'])->name('cart.items.store');
Route::post('/cart/items/{product}/decrease', [CartController::class, 'decrease'])->name('cart.items.decrease');
Route::delete('/cart/items/{product}', [CartController::class, 'destroy'])->name('cart.items.destroy');

// Оформление заказа
Route::middleware(OrderCheckoutAccess::class)->group(function () {
    Route::post('/order-checkout', [OrderCheckoutController::class, 'store'])->name('order_checkout.store');
});

// Аккаунты пользователей
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

// Каталог
Route::get('/catalog/product/{product}', [CatalogController::class, 'show'])->name('catalog.product');
Route::get('/catalog/category/{category:slug}', [CatalogController::class, 'category'])->name('catalog.category');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');

// Эмуляция кассы
Route::get('/sandbox/{hash}', [SandboxController::class, 'index'])->name('sandbox');

// Платежи
Route::post('/payments/success', [PaymentController::class, 'success'])->name('payments.success');
Route::post('/payments/fail', [PaymentController::class, 'fail'])->name('payments.fail');

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
