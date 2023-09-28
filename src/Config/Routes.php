<?php

use App\Controllers\AuthController;
use App\Controllers\CartController;
use App\Controllers\CategoryController;
use App\Controllers\IndexController;
use App\Controllers\OrderController;
use App\Controllers\ProductController;
use App\Core\Route\Route;
use App\Middleware\AuthMiddleware\AuthMiddleware;
use App\Middleware\CartMiddleware\CartMiddleware;

Route::get('main', [IndexController::class, 'showMainPage']);

Route::get('auth/logout', [AuthController::class, 'logout']);
Route::post('auth/login', [AuthController::class, 'login'], [AuthMiddleware::class, 'isAuth']);
Route::post('auth/register', [AuthController::class, 'registration'], [AuthMiddleware::class, 'isAuth']);
Route::get('auth/login', [AuthController::class, 'loginPage'], [AuthMiddleware::class, 'isAuth']);
Route::get('auth/register', [AuthController::class, 'registerPage'], [AuthMiddleware::class, 'isAuth']);

Route::get('product', [ProductController::class, 'getProducts']);
Route::get('product/([0-9]+)', [ProductController::class, 'getProductPage']);

Route::get('cart/show', [CartController::class, 'showCart']);
Route::get('cart/hide', [CartController::class, 'hideCart']);
Route::get('cart/([0-9]+)', [CartController::class, 'addToCart']);
Route::get('cart/increment/([0-9]+)', [CartController::class, 'incrementCount']);
Route::get('cart/decrement/([0-9]+)', [CartController::class, 'decrementCount']);
Route::get('cart/remove/([0-9]+)', [CartController::class, 'removeFromCart']);

Route::get('category/([a-zA-Z&-]+$)', [CategoryController::class, 'getCategoryPage']);

Route::get('checkout', [OrderController::class, 'checkout'], [[AuthMiddleware::class, 'onlyAuth'], [CartMiddleware::class, 'emptyCart']]);
Route::post('checkout', [OrderController::class, 'createOrder'], [[AuthMiddleware::class, 'onlyAuth'], [CartMiddleware::class, 'emptyCart']]);
