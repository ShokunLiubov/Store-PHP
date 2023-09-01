<?php

namespace App\Model;

use PDO;


class Model
{
    protected string $table;

    public function __construct()
    {
    }

    public function getTableName(): string
    {
        return $this->table;
    }

    public function getAll(): bool|array
    {
        $sql = 'SELECT * FROM  ' . $this->getTableName() . ' ORDER BY id DESC';
        $query = db()->dbQuery($sql);
        return $query->fetchAll();
    }

    public function getById(int $id): mixed
    {
        $sql = 'SELECT * FROM ' . self::getTableName() . ' WHERE id=:id';
        $query = db()->dbQuery($sql, ['id' => $id]);
        return $query->fetch();
    }

    public function getAllWithPaginate(int $page = 1, int $limit = 10, string $field, string $order, array $filters = []): array
    {
        $offset = ($page - 1) * $limit;
        $order = strtoupper($order);
        $whereClause = '';
        $bindValues = ['offset' => $offset, 'limit' => $limit];
        $bindTypes = ['offset' => PDO::PARAM_INT, 'limit' => PDO::PARAM_INT];

        if (!empty($filters)) {
            $whereParts = [];
            foreach ($filters as $column => $value) {
                $whereParts[] = "$column = :$column";
                $bindValues[$column] = $value;
                $bindTypes[$column] = PDO::PARAM_STR;
            }
            $whereClause = ' WHERE ' . implode(' AND ', $whereParts);
        }

        $sql = 'SELECT * FROM ' . $this->getTableName() . '
                ORDER BY '. $field .' ' . $order . '
                LIMIT :limit OFFSET :offset';
        $query = db()->dbQuery($sql, $bindValues, $bindTypes);

        return $query->fetchAll();
    }

    public function countAll(array $filters = []): int
    {
        $whereClause = '';
        $bindValues = [];
        $bindTypes = [];

        if (count($filters) > 0) {
            $whereParts = [];
            foreach ($filters as $column => $value) {
                $whereParts[] = "$column = :$column";
                $bindValues[$column] = $value;
                $bindTypes[$column] = PDO::PARAM_STR;
            }
            $whereClause = ' WHERE ' . implode(' AND ', $whereParts);
        }

        $sql = 'SELECT COUNT(*) AS total_count FROM ' . $this->getTableName() . $whereClause;
        $query = db()->dbQuery($sql, $bindValues, $bindTypes);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        return (int)$result['total_count'];
    }
}
