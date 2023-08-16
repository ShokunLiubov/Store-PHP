<?php

namespace App\Seeding;

use App\Utils\DataBase;
use App\Seeding\AbstractSeed;
use Faker\Factory as FakerFactory;
use Exception;

class OrderSeeding extends AbstractSeed
{
    public string $table = 'orders';

    public function seed()
    {
        try {
            $faker = FakerFactory::create();
            $db = new DataBase();

            // Create a link many to many with table 'category'
            for ($i = 1; $i <= $this->count; $i++) {

                $cart = [];
                $total = 0;
                //order
                $userIdQuery = "SELECT id FROM user ORDER BY RAND() LIMIT 1";
                $userId = $db->dbQuery($userIdQuery)->fetchColumn();

                $deliveryIdQuery = "SELECT id FROM delivery ORDER BY RAND() LIMIT 1";
                $deliveryId = $db->dbQuery($deliveryIdQuery)->fetchColumn();

                $sql = "INSERT INTO " . $this->getTableName() . "(`delivery_id`, `user_id`, `total`)
                                VALUES (?, ?, ?)";
                $orderId = $db->insertAndGetId($sql, [$deliveryId, $userId, $total]);

                //order item
                for ($j = 1; $j <= rand(1, 5); $j++) {
                    $productIdQuery = "SELECT id FROM product ORDER BY RAND() LIMIT 1";
                    $productId = $db->dbQuery($productIdQuery)->fetchColumn();

                    $sql = 'SELECT * FROM product WHERE id=:id';
                    $query = $db->dbQuery($sql, ['id' => $productId]);
                    $product = $query->fetch();
                    $price = $product['price'];
                    $quantity = $faker->numberBetween(1, 3);
                    $sql = "INSERT INTO  `order_item` (`quantity`, `price`, `product_id`, `order_id`)
                    VALUES (?, ?, ?, ?)";
                    $db->dbQuery($sql, [$quantity, $price, $productId, $orderId]);

                    $cart[] = [
                        'price' => $price,
                        'quantity' => $quantity
                    ];
                }

                foreach ($cart as $item) {
                    $total += $item['price'] * $item['quantity'];
                }

                $sql = "UPDATE " . $this->getTableName() . " SET `total` = ? WHERE `id` = ?";
                $query = $db->dbQuery($sql, [$total, $orderId]);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
