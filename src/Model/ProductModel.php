<?php

namespace App\Model;

use PDO;

class ProductModel extends Model
{
    protected string $table = 'product';

    public function getById(int $id): mixed
    {
        $sql = 'SELECT product.*, GROUP_CONCAT(category.name) AS category_names
                FROM ' . self::getTableName() . '
                JOIN product_category ON product.id = product_category.product_id
                JOIN category ON product_category.category_id = category.id
                WHERE product.id=:id
                GROUP BY product.id';
        $query = db()->dbQuery($sql, ['id' => $id]);
        return $query->fetch();
    }

//    public function getAllWithPaginate(int $page = 1, int $limit = 10): array
//    {
//        $offset = $page * $limit;
//        $sql = 'SELECT * FROM ' . self::getTableName() . ' LIMIT :limit OFFSET :offset';
//        $query = db()->dbQuery($sql, ['offset' => $offset, 'limit' => $limit], ['offset' => PDO::PARAM_INT, 'limit' => PDO::PARAM_INT]);
//        return $query->fetchAll();
//    }
}

