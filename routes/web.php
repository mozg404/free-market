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
use App\Http\Controllers\Cabinet\ProductStockItemsController;
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

// Товары
Route::middleware('auth')->prefix('/my')->group(function () {
    Route::get('/products', [CabinetProductController::class, 'index'])->name('my.products');
    Route::get('/products/create', [CabinetProductController::class, 'create'])->name('my.products.create');
    Route::post('/products', [CabinetProductController::class, 'store'])->name('my.products.store');
    Route::get('/products/{product}', [CabinetProductController::class, 'show'])->name('my.products.show');
    Route::get('/products/{product}/edit', [CabinetProductController::class, 'edit'])->name('my.products.edit');
    Route::put('/products/{product}', [CabinetProductController::class, 'update'])->name('my.products.update');
    Route::delete('/products/{product}', [CabinetProductController::class, 'destroy'])->name('my.products.delete');

    Route::prefix('/products/{product}')->group(function () {
        Route::get('/stock-items/create', [ProductStockItemsController::class, 'create'])->name('my.products.stock-items.create');
        Route::post('/stock-items', [ProductStockItemsController::class, 'store'])->name('my.products.stock-items.store');
        Route::get('/stock-items/{stock_item}/edit', [ProductStockItemsController::class, 'edit'])->name('my.products.stock-items.edit');
        Route::put('/stock-items/{stock_item}', [ProductStockItemsController::class, 'update'])->name('my.products.stock-items.update');
    });
});

Route::middleware('auth')->group(function () {
    // Профиль
    Route::get('/my/profile', [ProfileController::class, 'index'])->name('my.profile');

    // Товары и
//    Route::get('/my/products', [CabinetProductController::class, 'index'])->name('my.products');
//    Route::get('/my/products/create', [CabinetProductController::class, 'create'])->name('my.products.create');
//    Route::post('/my/products', [CabinetProductController::class, 'store'])->name('my.products.store');
//    Route::get('/my/products/{product}', [CabinetProductController::class, 'show'])->name('my.products.show');
//    Route::get('/my/products/{product}/edit', [CabinetProductController::class, 'edit'])->name('my.products.edit');
//    Route::put('/my/products/{product}', [CabinetProductController::class, 'update'])->name('my.products.update');
//    Route::delete('/my/products/{product}', [CabinetProductController::class, 'destroy'])->name('my.products.delete');


//    Route::prefix('/my/products/{product}')->group(function () {
//        Route::get('/stock-items/create', [ProductStockItemsController::class, 'create'])->name('my.products.stock-items.create');
//    });


    // Позиции товара
//    Route::get('/my/products/{product}/stock/create', [ProductStockController::class, 'create'])->name('my.products.stock.add');
//    Route::post('/cabinet/products/{product}/stock', [ProductStockController::class, 'store'])->name('cabinet.stock.store');
//    Route::get('/cabinet/stock/{stock_item}/edit', [ProductStockController::class, 'edit'])->name('cabinet.stock.edit');
//    Route::put('/cabinet/stock/{stock_item}', [ProductStockController::class, 'update'])->name('cabinet.stock.update');
//    Route::delete('/cabinet/stock/{stock_item}', [ProductStockController::class, 'destroy'])->name('cabinet.stock.destroy');

    Route::get('/cabinet/purchases', [PurchaseController::class, 'index'])->name('cabinet.purchases');
    Route::get('/cabinet/sales', [SaleController::class, 'index'])->name('cabinet.sales');

    // Заказы
    Route::get('/orders/{order}', [OrderController::class, 'show'])
        ->can('view', 'order')
        ->name('orders.show');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');

    // Баланс
    Route::get('/balance', [BalanceController::class, 'index'])->name('balance');
    Route::post('/balance/deposit', [BalanceController::class, 'deposit'])->name('balance.deposit');
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
