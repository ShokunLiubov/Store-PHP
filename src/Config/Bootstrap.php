<?php

use App\Controllers\AuthController;
use App\Controllers\CartController;
use App\Controllers\IndexController;
use App\Controllers\ProductController;
use App\Service\AuthService;
use App\Service\CartService;
use App\Service\ProductService;
use App\Core\Route;

$container = new App\Core\DIContainer();

$container->set(AuthController::class, function() {
    $authService = new AuthService();
    return new AuthController($authService);
});

$container->set(IndexController::class, function() {
    return new IndexController();
});

$container->set(CartController::class, function() {
    $cartService = new CartService();
    return new CartController($cartService);
});

$container->set(ProductController::class, function() {
    $productService = new ProductService();
    return new ProductController($productService);
});

Route::setContainer($container);

