<?php

namespace App\Service;
include_once('src/model/AuthModel.php');

use App\Model\ProductModel;
use Exception;

class ProductService
{
    public function __construct(protected ProductModel $productModel)
    {
    }
    /**
     * @throws Exception
     */
    public function getProducts(int $page, array $filters = []): array
    {
        $limit = 10;
        $path = 'main';
        $products = $this->productModel->getAllWithPaginate($page, $limit, $filters);
        $totalPages = $this->productModel->countAll($filters);
        $totalPages = $totalPages/$limit;
        if (!$products) {
            throw new Exception('Products not found!');
        }

        return ['products' => $products, 'totalPages' => $totalPages, 'currentPage' => (int)$page, 'path' => $path];
    }

    public function getProduct(int $id)
    {
        return $this->productModel->getById($id);
    }
}
