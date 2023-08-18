<?php

namespace App\Controllers;

use Exception;
use App\Model\ProductModel;
use Twig\Environment;
use App\Service\ProductService;

class ProductController
{
    public function getProducts(Environment $twig)
    {
        try {
            $model = new ProductModel('product');
            $products = $model->getAll();
            if (!$products) {
                throw new Exception('Products not found!');
            }
            $response = ['products' => $products, 'status' => 200];
            return $response['products'];
        } catch (Exception $e) {
            $error = $e->getMessage();
            echo $twig->render('Errors/Error404.twig', ['error' => $error]);
        }
    }

    public function getProductPage(Environment $twig, $id)
    {
        try {
            $product = (new ProductService())->getProduct($id);
            $response = ['product' => $product, 'status' => 200];
            echo $twig->render('ProductPage/ProductPage.twig', ['product' => $product]);
            return $response;
        } catch (Exception $e) {
            $error = $e->getMessage();
            echo $twig->render('Errors/Error404.twig', ['error' => $error]);
        }
    }
}
