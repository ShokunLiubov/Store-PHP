<?php

namespace App\Controllers;

use App\Controllers\ProductController;

class IndexController
{
    public function showMainPage($twig)
    {
        $productController = new ProductController();
        $products = $productController->getProducts($twig);

        echo $twig->render('MainPage/MainPage.twig', ['products' => $products]);
    }
}
