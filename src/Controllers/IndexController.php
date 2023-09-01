<?php

namespace App\Controllers;

use App\Model\ProductModel;
use App\Service\ProductService;
use App\Core\Response\Response;
use Exception;

class IndexController
{
    public function __construct(protected ProductService $productService)
    {
    }
    public function showMainPage(): Response
    {
        try {
            $page = $_GET["page"] ?? 1;
            $field = $_GET["field"] ?? 'id';
            $order = $_GET["order"] ?? 'asc';
            $filters = $_GET["filters"] ?? [];
            $productsData = $this->productService->getProducts($page, $field, $order, $filters);

            return response()->view('MainPage/MainPage', $productsData);

        } catch (Exception $e) {
            $error = $e->getMessage();
            return response()->view('Errors/Error404', ['error' => $error]);
        }

    }
}
