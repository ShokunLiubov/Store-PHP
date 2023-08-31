<?php

namespace App\Service;
include_once('src/model/AuthModel.php');

use App\Model\ProductModel;
use Exception;

class ProductService
{
    /**
     * @throws Exception
     */
    public function getProducts(int $page, array $filters = []): array
    {
        $model = new ProductModel();

        $products = $model->getAllWithPaginate($page, 10, $filters);
        $totalPages = $model->countAll(10, $filters);
        if (!$products) {
            throw new Exception('Products not found!');
        }

        return ['products' => $products, 'totalPages' => $totalPages, 'currentPage' => (int)$page];
    }

    public function getProduct(int $id)
    {
        $model = new ProductModel();
        return $model->getById($id);
    }
}
