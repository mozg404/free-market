<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\My\MyBalanceController;
use App\Http\Controllers\My\MyOrderController;
use App\Http\Controllers\My\MyPurchaseController;
use App\Http\Controllers\My\MySaleController;
use App\Http\Controllers\My\Product\ProductChangeCategoryController;
use App\Http\Controllers\My\Product\ProductChangeDescriptionController;
use App\Http\Controllers\My\Product\ProductChangeFeaturesController;
use App\Http\Controllers\My\Product\ProductChangeImageController;
use App\Http\Controllers\My\Product\ProductChangeInstructionController;
use App\Http\Controllers\My\Product\ProductChangeNameController;
use App\Http\Controllers\My\Product\ProductChangePriceController;
use App\Http\Controllers\My\Product\ProductCreateController;
use App\Http\Controllers\My\Product\ProductEditController;
use App\Http\Controllers\My\Product\ProductIndexController as CabinetProductController;
use App\Http\Controllers\My\Product\StockController;
use App\Http\Controllers\My\SettingsController;
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

// ---------------------------------------------
// My
// ---------------------------------------------

Route::middleware('auth')->prefix('/my')->group(function () {

    // ---------------------------------------------
    // Мои товары
    // ---------------------------------------------

    Route::prefix('/products')->group(function () {
        Route::get('/', [CabinetProductController::class, 'index'])->name('my.products');

        Route::get('/create', [ProductCreateController::class, 'index'])->name('my.products.create');
        Route::post('/create', [ProductCreateController::class, 'store'])->name('my.products.create.store');

        Route::middleware(['can:update,product'])->prefix('/{product}')->group(function () {
            Route::get('/edit', [ProductEditController::class, 'index'])->name('my.products.edit');

            Route::prefix('/change')->group(function () {
                Route::get('/name', [ProductChangeNameController::class, 'index'])->name('my.products.change.name');
                Route::patch('/name', [ProductChangeNameController::class, 'update'])->name('my.products.change.name.update');

                Route::get('/image', [ProductChangeImageController::class, 'index'])->name('my.products.change.image');
                Route::patch('/image', [ProductChangeImageController::class, 'update'])->name('my.products.change.image.update');

                Route::get('/category', [ProductChangeCategoryController::class, 'index'])->name('my.products.change.category');
                Route::patch('/category', [ProductChangeCategoryController::class, 'update'])->name('my.products.change.category.update');

                Route::get('/price', [ProductChangePriceController::class, 'index'])->name('my.products.change.price');
                Route::patch('/price', [ProductChangePriceController::class, 'update'])->name('my.products.change.price.update');

                Route::get('/features', [ProductChangeFeaturesController::class, 'index'])->name('my.products.change.features');
                Route::patch('/features', [ProductChangeFeaturesController::class, 'update'])->name('my.products.change.features.update');

                Route::get('/description', [ProductChangeDescriptionController::class, 'index'])->name('my.products.change.description');
                Route::patch('/description', [ProductChangeDescriptionController::class, 'update'])->name('my.products.change.description.update');

                Route::get('/instruction', [ProductChangeInstructionController::class, 'index'])->name('my.products.change.instruction');
                Route::patch('/instruction', [ProductChangeInstructionController::class, 'update'])->name('my.products.change.instruction.update');
            });

            Route::prefix('/stock')->group(function () {
                Route::get('/', [StockController::class, 'index'])->name('my.products.stock');
                Route::get('/create', [StockController::class, 'create'])->name('my.products.stock.create');
                Route::post('/', [StockController::class, 'store'])->name('my.products.stock.store');
                Route::get('/{stock_item}/edit', [StockController::class, 'edit'])->name('my.products.stock.edit');
                Route::put('/{stock_item}', [StockController::class, 'update'])->name('my.products.stock.update');
            });
        });
    });




    // ---------------------------------------------
    // Настройки профиля
    // ---------------------------------------------

    Route::get('/settings', [SettingsController::class, 'index'])->name('my.settings');
    Route::patch('/settings/change-avatar', [SettingsController::class, 'changeAvatar'])->name('my.settings.change-avatar');

    // ---------------------------------------------
    // Мои заказы
    // ---------------------------------------------

    Route::get('/orders/{order}/cancel', [MyOrderController::class, 'cancel'])
        ->can('cancel', 'order')
        ->name('my.orders.cancel');
    Route::get('/orders/{order}/pay', [MyOrderController::class, 'pay'])
        ->can('pay', 'order')
        ->name('my.orders.pay');
    Route::get('/orders/{order}', [MyOrderController::class, 'show'])
        ->can('view', 'order')
        ->name('my.orders.show');
    Route::get('/orders', [MyOrderController::class, 'index'])->name('my.orders');

    // ---------------------------------------------
    // Мой баланс
    // ---------------------------------------------

    Route::get('/balance', [MyBalanceController::class, 'index'])->name('my.balance');
    Route::post('/balance/deposit', [MyBalanceController::class, 'deposit'])->name('my.balance.deposit');

    // ---------------------------------------------
    // Мои покупки
    // ---------------------------------------------

    Route::get('/purchases', [MyPurchaseController::class, 'index'])->name('my.purchases');
    Route::get('/purchases/{stock_item}', [MyPurchaseController::class, 'show'])->name('my.purchases.show');

    // ---------------------------------------------
    // Мои продажи
    // ---------------------------------------------

    Route::get('/sales', [MySaleController::class, 'index'])->name('my.sales');
});

// ---------------------------------------------
// Корзина
// ---------------------------------------------

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/items/{product}', [CartController::class, 'store'])->name('cart.items.store');
Route::post('/cart/items/{product}/decrease', [CartController::class, 'decrease'])->name('cart.items.decrease');
Route::delete('/cart/items/{product}', [CartController::class, 'destroy'])->name('cart.items.destroy');

// ---------------------------------------------
// Оформление заказа
// ---------------------------------------------

Route::get('/checkout', [CheckoutController::class, 'cart'])
    ->middleware(CheckoutAccess::class)
    ->name('checkout');
Route::get('/checkout/express/{product}', [CheckoutController::class, 'express'])
    ->middleware(CheckoutExpressAccess::class)
    ->name('checkout.express');

// ---------------------------------------------
// Аккаунты пользователей
// ---------------------------------------------

Route::get('/users', [UserController::class, 'index'])->name('users');
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

// ---------------------------------------------
// Каталог
// ---------------------------------------------

Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');
Route::get('/catalog/product/{product}', [CatalogController::class, 'show'])->name('catalog.product');
Route::get('/catalog/{category:full_path}', [CatalogController::class, 'category'])->where('category', '.*')->name('catalog.category');

// ---------------------------------------------
// Эмуляция кассы
// ---------------------------------------------

Route::get('/demo/sandbox/{hash}', [SandboxController::class, 'index'])->name('sandbox');
Route::get('/demo/sandbox/{hash}/failed', [SandboxController::class, 'failed'])->name('sandbox.failed');
Route::get('/demo/sandbox/{hash}/success', [SandboxController::class, 'success'])->name('sandbox.success');
Route::get('/demo/sandbox/{hash}/cancelled', [SandboxController::class, 'cancelled'])->name('sandbox.cancelled');

// ---------------------------------------------
// Обработка внешнего платежа
// ---------------------------------------------

Route::get('/payment/callback', PaymentCallbackController::class)->name('payment.callback');

// ---------------------------------------------
// Авторизация
// ---------------------------------------------

Route::middleware('guest')->group(function () {
    Route::post('/registration/store', [RegistrationController::class, 'store'])->name('auth.registration.store');
    Route::get('/registration', [RegistrationController::class, 'show'])->name('auth.registration.show');
    Route::post('/login/store', [LoginController::class, 'store'])->name('login.store');
    Route::get('/login', [LoginController::class, 'show'])->name('login');
});
Route::get('/logout', LogoutController::class)
    ->middleware('auth')
    ->name('logout');

// ---------------------------------------------
// Главная
// ---------------------------------------------

Route::get('/', IndexController::class)->name('home');
