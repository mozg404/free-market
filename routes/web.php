<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Cabinet\BalanceController;
use App\Http\Controllers\Cabinet\BalanceDepositController;
use App\Http\Controllers\Cabinet\OrderController;
use App\Http\Controllers\Cabinet\ProfileController;
use App\Http\Controllers\Cabinet\PurchaseController;
use App\Http\Controllers\Cabinet\SaleController;
use App\Http\Controllers\Cabinet\ProductController as CabinetProductController;
use App\Http\Controllers\Cabinet\StockController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\OrderCheckoutController;
use App\Http\Controllers\FilepondImageController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SandboxController;
use App\Http\Controllers\TestController;
use App\Http\Middleware\OrderCheckoutAccess;
use Illuminate\Support\Facades\Route;

// FilePond
Route::get('/filepond/image/load', [FilepondImageController::class, 'load'])->name('filepond.image.load');
Route::post('/filepond/image/upload', [FilepondImageController::class, 'upload'])->name('filepond.image.upload');
Route::delete('/filepond/image/remove', [FilepondImageController::class, 'remove'])->name('filepond.image.remove');

if (config('app.env') === 'local') {
    Route::get('/test', [TestController::class, 'test']);
    Route::get('/test-page', [TestController::class, 'testPage']);
    Route::get('/test-page2', [TestController::class, 'testPage2']);
}

Route::middleware('auth')->group(function () {
    // Профиль
    Route::get('/cabinet/profile', [ProfileController::class, 'show'])->name('profile.show');

    // Товары и позиции товаров
    Route::get('/cabinet/products', [CabinetProductController::class, 'index'])->name('cabinet.products');
    Route::get('/cabinet/products/create', [CabinetProductController::class, 'create'])->name('cabinet.products.create');
    Route::post('/cabinet/products/store', [CabinetProductController::class, 'store'])->name('cabinet.products.store');
    Route::get('/cabinet/products/{product}/edit', [CabinetProductController::class, 'edit'])->name('cabinet.products.edit');
    Route::put('/cabinet/products/{product}/update', [CabinetProductController::class, 'update'])->name('cabinet.products.update');
    Route::delete('/cabinet/products/{product}', [CabinetProductController::class, 'destroy'])->name('cabinet.products.delete');
    Route::get('/cabinet/products/{product}/stock', [StockController::class, 'index'])->name('cabinet.stock.index');
    Route::get('/cabinet/products/{product}/stock/create', [StockController::class, 'create'])->name('cabinet.stock.create');
    Route::post('/cabinet/products/{product}/stock', [StockController::class, 'store'])->name('cabinet.stock.store');
    Route::get('/cabinet/stock/{stock_item}/edit', [StockController::class, 'edit'])->name('cabinet.stock.edit');
    Route::put('/cabinet/stock/{stock_item}', [StockController::class, 'update'])->name('cabinet.stock.update');
    Route::delete('/cabinet/stock/{stock_item}', [StockController::class, 'destroy'])->name('cabinet.stock.destroy');

    Route::get('/cabinet/orders', [OrderController::class, 'index'])->name('cabinet.orders');
    Route::get('/cabinet/purchases', [PurchaseController::class, 'index'])->name('cabinet.purchases');
    Route::get('/cabinet/sales', [SaleController::class, 'index'])->name('cabinet.sales');

    // Баланс
    Route::get('/cabinet/balance', [BalanceController::class, 'index'])->name('cabinet.balance');
    Route::get('/cabinet/balance/deposit', [BalanceDepositController::class, 'index'])->name('cabinet.balance.deposit');
    Route::post('/cabinet/balance/deposit', [BalanceDepositController::class, 'store'])->name('cabinet.balance.deposit.store');
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
    Route::get('/order-checkout/payment/{order}', [OrderCheckoutController::class, 'payment'])->name('order_checkout.payment');
    Route::post('/order-checkout/payment/{order}', [OrderCheckoutController::class, 'pay'])->name('order_checkout.pay');
});

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

Route::get('/', IndexController::class)->name('index');
