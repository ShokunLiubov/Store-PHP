<?php

namespace App\Controllers;

use App\Controllers\ProductController;
use Twig\Environment;

class IndexController
{
    public function showMainPage(Environment $twig): void
    {
        $productController = new ProductController();
        $products = $productController->getProducts($twig);

        echo $twig->render('Layout/PublicLayout.twig', [
            'main_content' => $twig->render('MainPage/MainPage.twig', ['products' => $products]),
            'cart_content' => $twig->render('Cart/Cart.twig')
        ]);
    }
}
