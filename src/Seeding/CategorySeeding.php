<?php

namespace App\Seeding;

use Exception;

class CategorySeeding extends AbstractSeed
{
    public string $table = 'category';

    public $categories = ['Perfumery', 'Hair', 'Face', 'Makeup', 'To men', 'Health & Care', 'Gifts', 'Clothes'];

    public function seed(): void
    {
        try {
            foreach ($this->categories as $category) {
                $slug = str_replace(' ', '-', strtolower($category));
                $sql = "INSERT INTO " . $this->getTableName() . " (`name`, `slug`)
                VALUES (:name, :slug)";
                db()->dbQuery($sql, ['name' => $category, 'slug' => $slug]);
            }
        } catch (Exception $e) {
        }
    }
}
