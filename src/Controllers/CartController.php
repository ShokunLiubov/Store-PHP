<?php

namespace App\Controllers;

use App\Controllers\ProductController;

class CartController
{
    public function showMainPage($twig)
    {
        $productController = new ProductController();
        $products = $productController->getProducts($twig);

        $isAuth = false;
        if (isset($_SESSION['auth-user'])) {
            $isAuth = true;
        }

        echo $twig->render('MainPage/MainPage.twig', ['products' => $products]);
    }
}
