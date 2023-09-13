<?php

namespace App\Model;

use App\Service\FilterService;
use PDO;

class ProductModel extends Model
{
    protected string $table = 'product';

    const DEFAULT_LIMIT = 10;

    public function __construct(protected FilterService $filterService)
    {
    }

    public function getById(int $id): mixed
    {
        $query = qb()::table($this->getTableName())
            ->selectWithConcatenation('product.*', 'category.name', 'category_names', ', ')
            ->innerJoin('product_category', 'product.id = product_category.product_id')
            ->innerJoin('category', 'product_category.category_id = category.id')
            ->where('product.id', '=', 'id', $id)
            ->groupBy('product.id')
            ->get();

        return $query->fetch();
    }

    public function getAllWithPaginate(int $page = 1, string $field, string $order, array $filters = [], int $limit = self::DEFAULT_LIMIT,): array
    {
        $query = qb()::table($this->getTableName())
            ->selectWithConcatenation('product.*', 'category.name', 'category_names', ', ')
            ->innerJoin('product_category', 'product.id = product_category.product_id')
            ->innerJoin('category', 'product_category.category_id = category.id')
            ->groupBy('product.id')
            ->orderBy($field, $order)
            ->limit($limit)
            ->offset($page);

        $this->filterService->productFilters($query, $filters);

        $products = $query->get()->fetchAll();
        $count = $this->count($filters);
        $totalPages = $this->calculateTotalPages($count, $limit);
        $order = strtolower($order);

        return [
            'products' => $products,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'field' => $field,
            'order' => $order
        ];
    }

    public function count(array $filters = []): int
    {
        $query = qb()::table($this->getTableName())
            ->selectCount('product.id')
            ->innerJoin('product_category', 'product.id = product_category.product_id')
            ->innerJoin('category', 'product_category.category_id = category.id');

        $this->filterService->productFilters($query, $filters);

        $result = $query->get()->fetch();

        return $result['total_count'] ?? 0;
    }


}

