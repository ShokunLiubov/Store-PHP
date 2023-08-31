<?php

namespace App\Core;

use App\Core\DIContainer;

class Route
{
    private static array $routes = [];
    private static ?DIContainer $container = null;

    public static function setContainer(DIContainer $container): void
    {
        self::$container = $container;
    }


    public static function get($path, $controllerAction, $middleware = []): void
    {
        self::add('GET', $path, $controllerAction, $middleware);
    }

    public static function post($path, $controllerAction, $middleware = []): void
    {
        self::add('POST', $path, $controllerAction, $middleware);
    }

    public static function put($path, $controllerAction, $middleware = []): void
    {
        self::add('PUT', $path, $controllerAction, $middleware);
    }

    public static function patch($path, $controllerAction, $middleware = []): void
    {
        self::add('PATCH', $path, $controllerAction, $middleware);
    }

    public static function delete($path, $controllerAction, $middleware = []): void
    {
        self::add('DELETE', $path, $controllerAction, $middleware);
    }

    private static function add($method, $path, $controllerAction, $middleware): void
    {
        self::$routes[] = [
            'method' => $method,
            'path' => $path,
            'middleware' => $middleware,
            'controllerAction' => $controllerAction
        ];
    }

    public static function dispatch($method, $path): void
    {

        foreach (self::$routes as $route) {
            // Use regular expressions to support variables like {id}
            $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $pattern = preg_replace('/\{[a-zA-Z0-9]+\}/', '([a-zA-Z0-9]+)', $route['path']);
            $pattern =  "@^" . BASE_URL . $pattern . "\/?$@D";

            if ($method === $route['method'] && preg_match($pattern, $path, $matches)) {

                array_shift($matches);  // Removing an exact match
                if (count($route['middleware']) > 0) {
                    call_user_func_array($route['middleware'], []);
                }

                if (is_callable($route['controllerAction'])) {
                    call_user_func_array($route['controllerAction'], array_merge($matches));
                } elseif (is_array($route['controllerAction'])) {
                    $controller = self::$container->get($route['controllerAction'][0]);
                    call_user_func_array([$controller, $route['controllerAction'][1]], array_merge($matches));
                }

                return;
            }
        }
        self::send404();
    }

    private static function send404(): void
    {
        response()->view('Errors/Error404');
    }
}