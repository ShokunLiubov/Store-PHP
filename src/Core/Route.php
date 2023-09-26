<?php

namespace App\Core;

use App\Core\Response\Response;

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
            'controllerAction' => $controllerAction,
            'middleware' => $middleware,
        ];
    }

    public static function dispatch($method, $path): void
    {
        foreach (self::$routes as $route) {
            $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $pattern = preg_replace('/\{[a-zA-Z0-9]+\}/', '([a-zA-Z0-9]+)', $route['path']);
            $pattern = "@^" . BASE_URL . $pattern . "\/?$@D";

            if ($method === $route['method'] && preg_match($pattern, $path, $getParams)) {
                array_shift($getParams);

                if (!empty($route['middleware'])) {
                    if (is_array($route['middleware'][0])) {
                        foreach ($route['middleware'] as $middleware) {
                            $middlewareObject = self::$container->get($middleware[0]);
                            $middlewareResponse = call_user_func_array([$middlewareObject, $middleware[1]], []);

                            if ($middlewareResponse instanceof Response) {

                                return;
                            }
                        }
                    } else {
                        $middlewareObject = self::$container->get($route['middleware'][0]);
                        $middlewareResponse = call_user_func_array([$middlewareObject, $route['middleware'][1]], []);
                    }

                    if ($middlewareResponse instanceof Response) {

                        return;
                    }
                }

                $controller = self::$container->get($route['controllerAction'][0]);
                $dependencies = self::$container->resolveMethodDependencies($controller, $route['controllerAction'][1], $getParams);
                call_user_func_array([$controller, $route['controllerAction'][1]], $dependencies);

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
