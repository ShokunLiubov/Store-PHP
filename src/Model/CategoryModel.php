<?php

namespace App\Model;

use PDO;

class CategoryModel extends Model
{
    protected string $table = 'category';

    public function getBySlug($slug)
    {
        $query = qb()::table($this->getTableName())
            ->select()
            ->where('slug', '=', 'slug', $slug)
            ->get();

        return $query->fetch();
    }

}
