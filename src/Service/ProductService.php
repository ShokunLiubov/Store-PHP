<?php

namespace App\Service;
include_once('src/model/AuthModel.php');

use App\Model\ProductModel;
use App\Utils\UrlUtils;
use Exception;

class ProductService extends Service
{
    public function __construct(protected ProductModel $productModel)
    {
    }
    /**
     * @throws Exception
     */
    public function getProducts(int $page, string $field, string $order, array $filters = []): array
    {
        $data = $this->productModel->getAllWithPaginate($page, $field, $order, $filters);
        $data['path'] = 'main';

        $urlParams = (new UrlUtils())->generateFilterUrlParams($filters);
        $data['filters'] = $urlParams;

        return $data;
    }

    public function getProduct(int $id)
    {
        return $this->productModel->getById($id);
    }
}
