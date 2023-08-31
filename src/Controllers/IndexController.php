<?php

namespace App\Controllers;

use App\Service\ProductService;
use App\Core\Response\Response;
use Exception;

class IndexController
{
    public function showMainPage(): Response
    {
        try {
            $page = $_GET["page"] ?? 1;
            $productsData = (new ProductService())->getProducts($page);

            return response()->view('MainPage/MainPage', $productsData);

        } catch (Exception $e) {
            $error = $e->getMessage();
            return response()->view('Errors/Error404', ['error' => $error]);
        }

    }
}
