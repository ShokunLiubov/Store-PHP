<?php

require_once('init.php');

use App\Config\Route;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use App\Twig\CustomTwigExtensions;
use App\Twig\TwigAuth;
use \Twig\TwigFunction;
use \Twig\Extension\DebugExtension;

$loader = new FilesystemLoader('src/Views');
$twig = new Environment($loader, ['cache' => false, 'debug' => true]);
$twig->addFunction(new TwigFunction('current_time', fn () => time()));
$twig->addExtension(new DebugExtension());
$twig->addExtension(new CustomTwigExtensions());
$twig->addExtension(new TwigAuth());

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestPath = $_SERVER['REQUEST_URI'];

Route::dispatch($requestMethod, $requestPath, $twig);
