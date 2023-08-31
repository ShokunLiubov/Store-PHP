<?php

require_once('init.php');

use App\Core\Route;

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestPath = $_SERVER['REQUEST_URI'];

Route::dispatch($requestMethod, $requestPath);
