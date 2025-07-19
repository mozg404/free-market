<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Cabinet\ProfileController;
use App\Http\Controllers\Cabinet\ShopController;
use App\Http\Controllers\Cabinet\ProductController as CabinetProductController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

Route::post('/uploader/upload', [FileUploadController::class, 'upload'])->name('uploader.upload');
Route::delete('/uploader/delete', [FileUploadController::class, 'delete'])->name('uploader.delete');
Route::get('/uploader/load', [FileUploadController::class, 'load'])->name('uploader.load');

Route::get('/test', [TestController::class, 'test']);

Route::get('/cabinet/profile', [ProfileController::class, 'show'])->name('profile.show');

Route::get('/cabinet/shops', [ShopController::class, 'index'])->name('cabinet.shops');
Route::get('/cabinet/shops/create', [ShopController::class, 'create'])->name('cabinet.shops.create');
Route::post('/cabinet/shops/store', [ShopController::class, 'store'])->name('cabinet.shops.store');
Route::get('/cabinet/shops/{shop}/edit', [ShopController::class, 'edit'])->name('cabinet.shops.edit');
Route::post('/cabinet/shops/{shop}/update', [ShopController::class, 'update'])->name('cabinet.shops.update');
Route::get('/cabinet/shops/{shop}', [ShopController::class, 'show'])->name('cabinet.shops.show');

Route::get('/cabinet/shops/{shop}/products', [CabinetProductController::class, 'index'])->name('cabinet.products');

Route::get('/product/{product}', [ProductsController::class, 'show'])->name('product.show');
Route::get('/catalog', [CatalogController::class, 'show'])->name('catalog');

Route::post('/registration/store', [RegistrationController::class, 'store'])->name('auth.registration.store');
Route::get('/registration', [RegistrationController::class, 'show'])->name('auth.registration.show');
Route::post('/login/store', [LoginController::class, 'store'])->name('login.store');
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::get('/logout', LogoutController::class)->name('logout');

Route::get('/', IndexController::class)->name('index');
