<?php

namespace App\Service;

use App\Model\ProductModel;
use App\Utils\UrlUtils;
use Exception;
use PDO;

class ProductService extends Service
{
    public function __construct(protected ProductModel $productModel)
    {
    }

    /**
     * @throws Exception
     */
    public function getProducts(array $filters = [], int $limit = 10): array
    {
        $field = request()->get("field", 'id');
        $order = request()->get("order", 'asc');

        $query = $this->productModel->query()
            ->select("product.*, GROUP_CONCAT(category.name SEPARATOR ' ') AS category_names")
            ->innerJoin('product_category', 'product.id = product_category.product_id')
            ->innerJoin('category', 'product_category.category_id = category.id')
            ->groupBy('product.id')
            ->orderBy($field, $order)
            ->distinct('product.id');

        $query = (new FilterService())->productFilters($query, $filters);

        $data = $query->paginate();

        $urlParams = (new UrlUtils())->generateFilterUrlParams($filters);

        $data['field'] = $field;
        $data['order'] = $order;
        $data['filters'] = $urlParams;
        $data['applied'] = $filters;
        $data['path'] = 'main';

        return $data;
    }

    /**
     * @throws Exception
     */
    public function getProduct(int $id): array | null
    {
        $product = $this->productModel->query()
            ->select("product.*, GROUP_CONCAT(category.name SEPARATOR ' ') AS category_names")
            ->innerJoin('product_category', 'product.id = product_category.product_id')
            ->innerJoin('category', 'product_category.category_id = category.id')
            ->where('product.id', '=', 'id', $id)
            ->groupBy('product.id')
            ->getOne();

        if(!$product) {
            throw new Exception('Product not found!');
        }

        return $product;
    }

    public function getMadeInCountries(): array
    {
        return $this->productModel->query()
            ->distinct('made')
            ->select()
            ->get(PDO::FETCH_COLUMN, 0);
    }
}
