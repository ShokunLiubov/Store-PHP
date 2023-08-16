<?php

namespace App\Controllers;

use Exception;
use App\Model\ProductModel;

class ProductController
{
    public function getProducts($twig)
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

    public function getProduct($twig, $id)
    {
        try {
            $model = new ProductModel('product');
            $product = $model->getById($id);
            if (!$product) {
                throw new Exception('Product not found!');
            }
            $response = ['product' => $product, 'status' => 200];
            echo $twig->render('ProductPage/ProductPage.twig', ['product' => $product]);
            return $response;
        } catch (Exception $e) {
            $error = $e->getMessage();
            echo $twig->render('Errors/Error404.twig', ['error' => $error]);
        }
    }
}
