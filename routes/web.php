<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\ProductController;
use App\Http\Controllers\Front\CheckoutController;
use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\SocialController;

// use App\Http\Controllers\TestController;

// use App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [HomeController::class, 'index'])
        ->name('home');

Route::get('/products', [ProductController::class, 'index'])
        ->name('products.index');

Route::get('/products/{product:slug}', [ProductController::class, 'show'])
        ->name('products.show');

Route::resource('cart', CartController::class);

Route::get('checkout', [CheckoutController::class, 'create'])->name('checkout');
Route::post('checkout', [CheckoutController::class, 'store']);


Route::get('auth/{provider}/redirect', [SocialLoginController::class, 'redirect'])
    ->name('auth.socilaite.redirect');
Route::get('auth/{provider}/callback', [SocialLoginController::class, 'callback'])
    ->name('auth.socilaite.callback');

    Route::get('auth/{provider}/user', [SocialController::class, 'index']);    

// require __DIR__.'/auth.php';

require __DIR__. '/dashboard.php';
