<?php

use App\Config\Route;
use App\Controllers\AuthController;
use App\Controllers\IndexController;
use App\Controllers\ProductController;

Route::get('', [IndexController::class, 'showMainPage']);

Route::get('auth/logout', [AuthController::class, 'logout']);
Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/register', [AuthController::class, 'registration']);
Route::get('auth/(register|login)', [AuthController::class, 'showAuthPage']);

Route::get('product', [ProductController::class, 'getProducts']);
Route::get('product/([0-9]+)', [ProductController::class, 'getProduct']);
