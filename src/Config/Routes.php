<?php

use App\Config\Route;
use App\Controllers\AuthController;

Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/register', [AuthController::class, 'registration']);
Route::get('auth/(register|login)', [AuthController::class, 'showAuthPage']);
