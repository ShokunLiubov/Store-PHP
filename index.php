<?php

require_once('init.php');

use App\Config\Route;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

$loader = new FilesystemLoader('src/Views');
$twig = new Environment($loader);

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestPath = $_SERVER['REQUEST_URI'];

Route::dispatch($requestMethod, $requestPath, $twig);
