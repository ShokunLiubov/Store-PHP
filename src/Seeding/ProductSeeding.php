<?php

namespace App\Seeding;

use Exception;
use Faker\Factory as FakerFactory;

class ProductSeeding extends AbstractSeed
{
    public string $table = 'product';

    public function seed(): void
    {
        $faker = FakerFactory::create();

        for ($i = 1; $i <= $this->count; $i++) {
            $title = $faker->words(3, true);
            $slug = str_replace(' ', '-', strtolower($title));
            $image = $faker->imageUrl(300, 400, 'cosmetics');
            $description = $faker->paragraph();
            $price = $faker->randomNumber(3);
            $quantity = $faker->numberBetween(5, 50);
            $made = $faker->country();

            $sql = "INSERT INTO " . $this->getTableName() . " (`title`, `slug`, `image`, `description`, `price`, `quantity`, `made`)
                    VALUES (:title, :slug, :image, :description, :price, :quantity, :made)";
            db()->dbQuery($sql, ['title' => $title, 'slug' => $slug, 'image' => $image,
                'description' => $description, 'price' => $price,
                'quantity' => $quantity, 'made' => $made]);
        }

            // Create a link many to many with table 'category'
        $productIdQuery = "SELECT id FROM product";$products = db()->dbQuery($productIdQuery)->fetchAll();

        foreach ($products as $product) {
            $categoryIdQuery = "SELECT id FROM category ORDER BY RAND() LIMIT 1";
            $categoryId = db()->dbQuery($categoryIdQuery)->fetchColumn();

            $sqlСoncurrency = "SELECT * FROM product_category WHERE product_id = :product_id AND category_id = :category_id";
            $exists = db()->dbQuery($sqlСoncurrency, ['product_id' => $product['id'], 'category_id' => $categoryId]);
            if ($exists->rowCount() > 0) {
                continue;
            }

            $insertRelation = "INSERT INTO product_category (product_id, category_id) VALUES (:product_id, :category_id)";
            db()->dbQuery($insertRelation, ['product_id' => $product['id'], 'category_id' => $categoryId]);

        }
    }
}
