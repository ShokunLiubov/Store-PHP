<?php

use App\Controllers\AuthController;
use App\Controllers\CartController;
use App\Controllers\IndexController;
use App\Controllers\ProductController;
use \App\Controllers\CategoryController;
use App\Core\Route;

Route::get('main', [IndexController::class, 'showMainPage']);

Route::get('auth/logout', [AuthController::class, 'logout']);
Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/register', [AuthController::class, 'registration']);
Route::get('auth/login', [AuthController::class, 'loginPage']);
Route::get('auth/register', [AuthController::class, 'registerPage']);

Route::get('product', [ProductController::class, 'getProducts']);
Route::get('product/([0-9]+)', [ProductController::class, 'getProductPage']);

Route::get('cart/show', [CartController::class, 'showCart']);
Route::get('cart/hide', [CartController::class, 'hideCart']);
Route::get('cart/([0-9]+)', [CartController::class, 'addToCart']);
Route::get('cart/increment/([0-9]+)', [CartController::class, 'incrementCount']);
Route::get('cart/decrement/([0-9]+)', [CartController::class, 'decrementCount']);
Route::get('cart/remove/([0-9]+)', [CartController::class, 'removeFromCart']);

Route::get('category/([a-zA-Z&-]+$)', [CategoryController::class, 'getCategoryPage']);
