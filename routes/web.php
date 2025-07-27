<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Cabinet\ProfileController;
use App\Http\Controllers\Cabinet\PurchaseController;
use App\Http\Controllers\Cabinet\ShopController;
use App\Http\Controllers\Cabinet\ProductController as CabinetProductController;
use App\Http\Controllers\Cabinet\StockController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\OrderCheckoutController;
use App\Http\Controllers\FilepondImageController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\TestController;
use App\Http\Middleware\OrderCheckoutAccess;
use Illuminate\Support\Facades\Route;

// FilePond
Route::get('/filepond/image/load', [FilepondImageController::class, 'load'])->name('filepond.image.load');
Route::post('/filepond/image/upload', [FilepondImageController::class, 'upload'])->name('filepond.image.upload');
Route::delete('/filepond/image/remove', [FilepondImageController::class, 'remove'])->name('filepond.image.remove');

Route::get('/test', [TestController::class, 'test']);

Route::get('/cabinet/profile', [ProfileController::class, 'show'])->name('profile.show');

//Route::get('/cabinet/shops', [ShopController::class, 'index'])->name('cabinet.shops');
//Route::get('/cabinet/shops/create', [ShopController::class, 'create'])->name('cabinet.shops.create');
//Route::post('/cabinet/shops/store', [ShopController::class, 'store'])->name('cabinet.shops.store');
//Route::get('/cabinet/shops/{shop}/edit', [ShopController::class, 'edit'])->name('cabinet.shops.edit');
//Route::post('/cabinet/shops/{shop}/update', [ShopController::class, 'update'])->name('cabinet.shops.update');
//Route::get('/cabinet/shops/{shop}', [ShopController::class, 'show'])->name('cabinet.shops.show');

// Кабинет

Route::get('/cabinet/products', [CabinetProductController::class, 'index'])->name('cabinet.products');
Route::get('/cabinet/products/create', [CabinetProductController::class, 'create'])->name('cabinet.products.create');
Route::post('/cabinet/products/store', [CabinetProductController::class, 'store'])->name('cabinet.products.store');
Route::get('/cabinet/products/{product}/edit', [CabinetProductController::class, 'edit'])->name('cabinet.products.edit');
Route::post('/cabinet/products/{product}/update', [CabinetProductController::class, 'update'])->name('cabinet.products.update');
Route::delete('/cabinet/products/{product}', [CabinetProductController::class, 'destroy'])->name('cabinet.products.delete');

Route::get('/cabinet/products/{product}/stock', [StockController::class, 'index'])->name('cabinet.stock.index');
Route::get('/cabinet/products/{product}/stock/create', [StockController::class, 'create'])->name('cabinet.stock.create');
Route::post('/cabinet/products/{product}/stock', [StockController::class, 'store'])->name('cabinet.stock.store');
Route::get('/cabinet/stock/{stock_item}/edit', [StockController::class, 'edit'])->name('cabinet.stock.edit');
Route::put('/cabinet/stock/{stock_item}', [StockController::class, 'update'])->name('cabinet.stock.update');
Route::delete('/cabinet/stock/{stock_item}', [StockController::class, 'destroy'])->name('cabinet.stock.destroy');

// Кабинет - Мои покупки
Route::get('/cabinet/purchases', [PurchaseController::class, 'index'])->name('cabinet.purchases');


// Корзина
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/delete/{product}', [CartController::class, 'delete'])->name('cart.delete');
Route::delete('/cart/clean', [CartController::class, 'clean'])->name('cart.clean');

// Оформление заказа
Route::middleware(OrderCheckoutAccess::class)->group(function () {
    Route::post('/order-checkout', [OrderCheckoutController::class, 'store'])->name('order_checkout.store');
    Route::get('/order-checkout/payment/{order}', [OrderCheckoutController::class, 'payment'])->name('order_checkout.payment');
    Route::post('/order-checkout/payment/{order}', [OrderCheckoutController::class, 'pay'])->name('order_checkout.pay');
});

// Каталог товаров
Route::get('/product/{product}', [ProductsController::class, 'show'])->name('product.show');
Route::get('/catalog', [CatalogController::class, 'show'])->name('catalog');

// Авторизация
Route::post('/registration/store', [RegistrationController::class, 'store'])->name('auth.registration.store');
Route::get('/registration', [RegistrationController::class, 'show'])->name('auth.registration.show');
Route::post('/login/store', [LoginController::class, 'store'])->name('login.store');
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::get('/logout', LogoutController::class)->name('logout');

Route::get('/', IndexController::class)->name('index');
