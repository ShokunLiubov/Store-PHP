<?php

namespace App\Model;

use App\Model\Model;
use App\Utils\DataBase;

class ProductModel extends Model
{
    public function getById(int $id)
    {

        $db = new DataBase();
        $sql = 'SELECT product.*, GROUP_CONCAT(category.name) AS category_names
                FROM ' . self::getTableName() . '
                JOIN product_category ON product.id = product_category.product_id
                JOIN category ON product_category.category_id = category.id
                WHERE product.id=:id
                GROUP BY product.id';
        $query = $db->dbQuery($sql, ['id' => $id]);
        return $query->fetch();
    }
}

