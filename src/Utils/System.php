<?php

use \Twig\Loader\FilesystemLoader;
use \Twig\Environment;

function template(string $path, array $vars = []): string
{
    static $twig;

    if ($twig === null) {
        $loader = new FilesystemLoader('src/views');
        $twig = new Environment($loader, [
            'cache' => false,
            'auto_reload' => true,
            'autoescape' => false,
            'strict_variables' => true
        ]);
    }

    return $twig->render("$path.twig", $vars);
}


function parseUrl(string $url, array $routes): array
{
    $res = [
        'controller' => 'errors/e404',
        'params' => []
    ];
    foreach ($routes as $route) {
        $matches = [];

        if (preg_match($route['test'], $url, $matches)) {
            $res['controller'] = $route['controller'];
            $res['layout'] = $route['layout'];

            if (isset($route['params'])) {
                foreach ($route['params'] as $name => $num) {
                    $res['params'][$name] = $matches[$num];
                }
            }

            break;
        }
    }

    return $res;
}
