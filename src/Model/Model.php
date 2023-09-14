<?php

namespace App\Model;

use PDO;
use \App\Contracts\Model as IModel;


class Model implements IModel
{
    protected string $table;
    const DEFAULT_LIMIT = 10;

    public function __construct()
    {
    }

    protected function calculateTotalPages(int $count, int $limit): int
    {
        return ceil($count / $limit);
    }


    public function getTableName(): string
    {
        return $this->table;
    }

    public function getAll(): bool|array
    {
        $query = qb()::table($this->getTableName())
            ->select()
            ->orderBy('id', 'DESC')
            ->get();
        return $query->fetchAll();
    }

    public function getById(int $id): mixed
    {
        $query = qb()::table($this->getTableName())
            ->select()
            ->where('id', '=', 'id', $id)
            ->groupBy('product.id')
            ->get();

        return $query->fetch();
    }

    public function getAllWithPaginate(int $page = 1, string $field, string $order, array $filters = [], int $limit = 10): array
    {
        $query = qb()::table($this->getTableName())
            ->selectWithConcatenation('product.*', 'category.name', 'category_names', ', ')
            ->orderBy($field, $order)
            ->limit($limit)
            ->offset($page);

        $data = $query->get()->fetchAll();
        $count = $this->count();
        $totalPages = $this->calculateTotalPages($count, $limit);
        $order = strtolower($order);

        return [
            'data' => $data,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'field' => $field,
            'order' => $order
        ];
    }

    public function count(array $filters = []): int
    {
        $query = qb()::table($this->getTableName())
            ->selectCount()->get();

        $result = $query->fetch();

        return $result['total_count'] ?? 0;
    }
}
