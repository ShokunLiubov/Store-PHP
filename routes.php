<?php

class Route
{
    public string $int = '[1-9]+\d*';
    private string $normUrl = '[1-9aA-zZ_-]+';


    public function getRoutes(): array
    {
        return [
            [
                'test' => '/^\/?$/',
                'controller' => 'index'
            ],
            [
                'test' => '/^auth\/?$/',
                'controller' => 'AuthController'
            ],
            [
                'test' => "/^add\/({$this->int})\/?$/",
                'controller' => 'AuthController',
                'params' => ['id' => 1]
            ],
            [
                'test' => "/^catalog\/({$this->int})\/({$this->int})\/?$/",
                'controller' => 'CatalogController',
                'params' => ['categoryId' => 1, 'productId' => 2]
            ],
        ];
    }
}
