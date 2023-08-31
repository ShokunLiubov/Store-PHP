<?php

namespace App\Seeding;

use App\Core\DataBase\DataBase;
use Exception;

class CategorySeeding extends AbstractSeed
{
    public string $table = 'category';

    public $categories =  ['Perfumery',  'Hair', 'Face',  'Makeup',  'To men',  'Health & Care', 'Gifts', 'Clothes'];

    public function seed()
    {
        try {
            $db = new DataBase();

            foreach ($this->categories as $category) {
                $slug = str_replace(' ', '-', strtolower($category));
                $sql = "INSERT INTO " . $this->getTableName() . " (`name`, `slug`)
                VALUES (?, ?)";
                $db->dbQuery($sql, [$category, $slug]);
            }
        } catch (Exception $e) {
        }
    }
}
