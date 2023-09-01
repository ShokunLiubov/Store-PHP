<?php

use App\Controllers\AuthController;
use App\Controllers\CartController;
use App\Controllers\IndexController;
use App\Controllers\ProductController;
use \App\Controllers\CategoryController;
use App\Service\AuthService;
use App\Service\CartService;
use App\Service\ProductService;
use \App\Service\CategoryService;
use \App\Model\CategoryModel;
use \App\Model\ProductModel;
use \App\Model\AuthModel;
use App\Core\Route;

$container = new App\Core\DIContainer();

$container->set(AuthController::class, function() {
    $authService = new AuthService(new AuthModel());
    return new AuthController($authService);
});

$container->set(IndexController::class, function() {
    $productService = new ProductService(new ProductModel());
    return new IndexController($productService);
});

$container->set(CartController::class, function() {
    $cartService = new CartService();
    return new CartController($cartService);
});

$container->set(ProductController::class, function() {
    $productService = new ProductService(new ProductModel());
    return new ProductController($productService);
});

$container->set(CategoryController::class, function() {
    $categoryService = new CategoryService(new CategoryModel());
    return new CategoryController($categoryService);
});

Route::setContainer($container);

