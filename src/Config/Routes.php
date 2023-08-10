<?php

use App\Config\Route;
use App\Controllers\AuthController;
use App\Controllers\IndexController;
use App\Controllers\ProductController;

Route::get('', [IndexController::class, 'showMainPage']);
Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/register', [AuthController::class, 'registration']);
Route::get('auth/(register|login)', [AuthController::class, 'showAuthPage']);
Route::get('', [ProductController::class, 'getProducts']);
