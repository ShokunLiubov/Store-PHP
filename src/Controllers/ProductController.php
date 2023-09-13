<?php

namespace App\Controllers;

use App\Core\Response\Response;
use Exception;
use App\Service\ProductService;

class ProductController extends Controller
{
    public function __construct(protected ProductService $productService)
    {
    }

    public function getProductPage(int $id, ProductService $productService): Response
    {
        try {
            $product = $productService->getProduct($id);
            return response()->view('ProductPage/ProductPage', ['product' => $product]);

        } catch (Exception $e) {
            $error = $e->getMessage();
            return response()->view('Errors/Error404', ['error' => $error]);
        }
    }
}
