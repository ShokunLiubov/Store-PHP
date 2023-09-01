<?php

namespace App\Model;

use PDO;

class ProductModel extends Model
{
    protected string $table = 'product';

    public function getById(int $id): mixed
    {
        $sql = "SELECT product.*, GROUP_CONCAT(category.name SEPARATOR ', ') AS category_names
                FROM " . $this->getTableName() . "
                JOIN product_category ON product.id = product_category.product_id
                JOIN category ON product_category.category_id = category.id
                WHERE product.id=:id
                GROUP BY product.id";
        $query = db()->dbQuery($sql, ['id' => $id]);
        return $query->fetch();
    }

}

