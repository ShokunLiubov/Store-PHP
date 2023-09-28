<?php

use App\Core\DIContainer\DIContainer;
use App\Core\Route\Route;

$container = new DIContainer();

Route::setContainer($container);

