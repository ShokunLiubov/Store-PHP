<?php

namespace App\Config;

class Route
{
    private static $routes = [];

    public static function get($path, $controllerAction, $middleware = [])
    {
        self::add('GET', $path, $controllerAction, $middleware);
    }

    public static function post($path, $controllerAction, $middleware = [])
    {
        self::add('POST', $path, $controllerAction, $middleware);
    }

    public static function put($path, $controllerAction, $middleware = [])
    {
        self::add('PUT', $path, $controllerAction, $middleware);
    }

    public static function patch($path, $controllerAction, $middleware = [])
    {
        self::add('PATCH', $path, $controllerAction, $middleware);
    }

    public static function delete($path, $controllerAction, $middleware = [])
    {
        self::add('DELETE', $path, $controllerAction, $middleware);
    }

    private static function add($method, $path, $controllerAction, $middleware)
    {
        self::$routes[] = [
            'method' => $method,
            'path' => $path,
            'middleware' => $middleware,
            'controllerAction' => $controllerAction
        ];
    }

    public static function dispatch($method, $path, $twig)
    {
        foreach (self::$routes as $route) {
            // Use regular expressions to support variables like {id}
            $pattern = preg_replace('/\{[a-zA-Z0-9]+\}/', '([a-zA-Z0-9]+)', $route['path']);
            $pattern =  "@^" . BASE_URL . $pattern . "\/?$@D";
            if ($method === $route['method'] && preg_match($pattern, $path, $matches)) {

                array_shift($matches);  // Removing an exact match
                if (count($route['middleware']) < 0) {
                    call_user_func_array($route['middleware'], []);
                }
                // We call the appropriate controller and action
                if (is_callable($route['controllerAction'])) {
                    call_user_func_array($route['controllerAction'], array_merge([$twig], $matches));
                } elseif (is_array($route['controllerAction'])) {
                    $controller = new $route['controllerAction'][0]();
                    call_user_func_array([$controller, $route['controllerAction'][1]], array_merge([$twig], $matches));
                }
                return;
            }
        }

        header("HTTP/1.0 404 Not Found");
        echo $twig->render('errors/v_404.twig');
    }

    private static function send404($twig)
    {
        header("HTTP/1.1 404 Not Found");
        echo $twig->render('errors/v_404.twig');
    }
}
