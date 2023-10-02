<?php

namespace App\Seeding;

use Exception;
use Faker\Factory as FakerFactory;

class ProductSeeding extends AbstractSeeding
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

            $this->query()
                ->table($this->table)
                ->insert([
                    'title' => $title, 'slug' => $slug,
                    'image' => $image, 'description' => $description,
                    'price' => $price, 'quantity' => $quantity,
                    'made' => $made])
                ->get();

        }

            // Create a link many to many with table 'category'
        $products = $this->query()
            ->table($this->table)
            ->select()
            ->get();

        foreach ($products as $product) {
            for ($i = 1; $i <= $faker->numberBetween(1, 3); $i++) {

                $categoryId = $this->query()
                    ->table('category')
                    ->select('id')
                    ->orderBy('random')
                    ->limit(1)
                    ->getOne();

                $exists = $this->query()
                    ->table('product_category')
                    ->select()
                    ->where('product_id', '=', 'product', $product['id'])
                    ->where('category_id', '=', 'category', $categoryId['id'])
                    -> get();

                if (!empty($exists)) {
                    continue;
                }

                $this->query()
                    ->table('product_category')
                    ->insert(['product_id' => $product['id'], 'category_id' => $categoryId['id']])
                    -> get();
            }
        }
    }
}
