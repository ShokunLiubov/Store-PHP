<?php

include_once('init.php');

$pageCanonical = HOST . BASE_URL;
$uri = $_SERVER['REQUEST_URI'];
$badUrl = BASE_URL . '/index.php';


if (strpos($uri, $badUrl) === 0) {
    $controllerName = 'errors/e404';
} else {
    $routes = new Route();
    $routes = $routes->getRoutes();
    $url = $_GET['querysystemurl'] ?? '';
    $routerRes = parseUrl($url, $routes);
    d($routerRes);
    $controllerName = $routerRes['controller'];
    define('URL_PARAMS', $routerRes['params']);

    $urlLen = strlen($url);

    if ($urlLen > 0 && $url[$urlLen - 1] == '/') {
        $url = substr($url, 0, $urlLen - 1);
    }

    $pageCanonical .= $url;
}

$path = PATH_PREFIX . $controllerName . PATH_POSTFIX;
$pageTitle = $pageContent = '';
if (!file_exists($path)) {
    $controllerName = 'errors/e404';
}

include_once($path);



$html = template('base/v_main', [
    'title' => $pageTitle,
    'content' => $pageContent,
    'canonical' => $pageCanonical
]);

echo $html;
