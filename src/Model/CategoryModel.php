<?php

namespace App\Model;

use App\Model\Model;
use PDO;

class CategoryModel extends Model
{
    protected string $table = 'category';

    public function getBySlug($slug)
    {
        $sql = 'SELECT * FROM ' . self::getTableName() . ' WHERE slug=:slug';
        $query = db()->dbQuery($sql, ['slug' => $slug]);
        return $query->fetch();
    }

    public function getAllWithPaginate(int $page = 1, int $limit = 10, string $field, string $order, array $filters = []): array
    {
        $offset = ($page - 1) * $limit;
        $categoryId = $filters['category'] ?? '';


        $sql = "SELECT DISTINCT product.*, GROUP_CONCAT(category.name SEPARATOR ' ') AS category_names
                FROM product
                JOIN product_category ON product.id = product_category.product_id
                JOIN category ON product_category.category_id = category.id
                WHERE category.id=:id
                GROUP BY product.id
                ORDER BY ". $field ." " . $order . "
                LIMIT :limit OFFSET :offset";

        $query = db()->dbQuery($sql, ['id' => $categoryId, 'offset' => $offset, 'limit' => $limit], ['id' => PDO::PARAM_STR, 'offset' => PDO::PARAM_INT, 'limit' => PDO::PARAM_INT]);

        return $query->fetchAll();
    }

    public function countAll(array $filters = []): int
    {
        $categoryId = $filters['category'] ?? '';

        $sql = 'SELECT COUNT(DISTINCT product.id) AS total_count
                FROM product
                JOIN product_category ON product.id = product_category.product_id
                JOIN category ON product_category.category_id = category.id
                WHERE category.id=:id';

        $query = db()->dbQuery($sql, ['id' => $categoryId]);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total_count'];
    }
}
