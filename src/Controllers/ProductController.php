<?php

namespace App\Controllers;

use App\Core\Response\Response;
use App\Service\ProductService;

class ProductController extends Controller
{
    public function __construct(protected ProductService $productService)
    {
    }

    /**
     * @throws \Exception
     */
    public function getProductPage(int $id): Response
    {
            $data['product'] = $this->productService->getProduct($id);

            return response()->view('Pages/ProductPage/ProductPage', $data);
    }
}
