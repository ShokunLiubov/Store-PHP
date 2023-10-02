<?php

namespace App\Seeding;

use Exception;

class CategorySeeding extends AbstractSeeding
{
    public string $table = 'category';

    public array $categories = ['Perfumery', 'Hair', 'Face', 'Makeup', 'To men', 'Health & Care', 'Gifts', 'Clothes'];

    public function seed(): void
    {
        foreach ($this->categories as $category) {

            $slug = str_replace(' ', '-', strtolower($category));

            $this->query()
                ->table($this->table)
                ->insert(['name' => $category, 'slug' => $slug])
                -> get();
        }
    }
}
