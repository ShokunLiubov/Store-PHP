<?php

namespace App\Model;

use App\Service\FilterService;
use App\Utils\UrlUtils;
use PDO;

class ProductModel extends Model
{
    protected string $table = 'product';

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
        $urlParams = (new UrlUtils())->generateFilterUrlParams($filters);

        return [
            'products' => $products,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'field' => $field,
            'order' => $order,
            'filters' => $urlParams,
            'applied' => $filters
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

    public function  getMadeInCountries(): array
    {
        $query = qb()::table($this->getTableName())
            ->select('DISTINCT made')
            ->get();

        return $query->fetchAll(PDO::FETCH_COLUMN, 0);
    }

}

