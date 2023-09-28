<?php

namespace App\Core\Route;

use App\Core\DIContainer\DIContainer;
use App\Core\Response\Response;
use Exception;

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
        try {
            foreach (self::$routes as $route) {
                $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
                $pattern = preg_replace('/\{[a-zA-Z0-9]+\}/', '([a-zA-Z0-9]+)', $route['path']);
                $pattern = "@^" . BASE_URL . $pattern . "\/?$@D";

                if ($method === $route['method'] && preg_match($pattern, $path, $getParams)) {
                    array_shift($getParams);

                    $response = self::applyMiddleware($route['middleware']);

                    if ($response) {

                        return;
                    }

                    $controller = self::$container->get($route['controllerAction'][0]);
                    $dependencies = self::$container->resolveMethodDependencies($controller, $route['controllerAction'][1], $getParams);
                    call_user_func_array([$controller, $route['controllerAction'][1]], $dependencies);

                    return;
                }
            }

            throw new Exception('Page not found');
        } catch (Exception $e) {
            $error = $e->getMessage();
            self::sendError($error);
        }
    }

    /**
     * @throws Exception
     */
    private static function applyMiddleware(array $middlewares): bool
    {
        $response = false;
        if (!empty($middlewares)) {

            if (is_array($middlewares[0])) {

                foreach ($middlewares as $middleware) {
                    $middlewareObject = self::$container->get($middleware[0]);
                    $middlewareResponse = call_user_func_array([$middlewareObject, $middleware[1]], []);

                    if ($middlewareResponse instanceof Response) {

                        return $response = true;
                    }
                }
            } else {
                $middlewareObject = self::$container->get($middlewares[0]);
                $middlewareResponse = call_user_func_array([$middlewareObject, $middlewares[1]], []);

                if ($middlewareResponse instanceof Response) {

                    return $response = true;
                }
            }
        }

        return $response;
    }

    private static function sendError(string $error = 'Something error...'): Response
    {
        return response()->view('Errors/Error', ['error' => $error]);
    }
}
