<?php

use App\Config\Route;
use App\Controllers\AuthController;
use App\Controllers\IndexController;
use App\Controllers\ProductController;
use App\Controllers\CartController;

Route::get('', [IndexController::class, 'showMainPage']);

Route::get('auth/logout', [AuthController::class, 'logout']);
Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/register', [AuthController::class, 'registration']);
Route::get('auth/(register|login)', [AuthController::class, 'showAuthPage']);

Route::get('product', [ProductController::class, 'getProducts']);
Route::get('product/([0-9]+)', [ProductController::class, 'getProduct']);

Route::get('cart/show', [CartController::class, 'showCart']);
Route::get('cart/hide', [CartController::class, 'hideCart']);
Route::get('cart/([0-9]+)', [CartController::class, 'addToCart']);
