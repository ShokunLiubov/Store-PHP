<?php

namespace App\Seeding;

use Exception;
use Faker\Factory as FakerFactory;

class OrderSeeding extends AbstractSeed
{
    public string $table = 'orders';

    public function seed(): void
    {
        try {
            $faker = FakerFactory::create();

            for ($i = 1; $i <= $this->count; $i++) {

                $cart = [];
                $total = 0;
                //order
                $userIdQuery = "SELECT id FROM user ORDER BY RAND() LIMIT 1";
                $userId = db()->dbQuery($userIdQuery)->fetchColumn();

                $deliveryIdQuery = "SELECT id FROM delivery ORDER BY RAND() LIMIT 1";
                $deliveryId = db()->dbQuery($deliveryIdQuery)->fetchColumn();
                $sql = "INSERT INTO " . $this->getTableName() . "(`delivery_id`, `user_id`, `total`)
                                VALUES (:delivery_id, :user_id, :total)";
                $orderId = db()->insertAndGetId($sql, ['delivery_id' => $deliveryId, 'user_id' => $userId, 'total' => $total]);
                //order item
                for ($j = 1; $j <= rand(1, 5); $j++) {
                    $productIdQuery = "SELECT id FROM product ORDER BY RAND() LIMIT 1";
                    $productId = db()->dbQuery($productIdQuery)->fetchColumn();

                    $sql = 'SELECT * FROM product WHERE id=:id';
                    $query = db()->dbQuery($sql, ['id' => $productId]);
                    $product = $query->fetch();
                    $price = $product['price'];
                    $quantity = $faker->numberBetween(1, 3);
                    $sql = "INSERT INTO  `order_item` (`quantity`, `price`, `product_id`, `order_id`)
                    VALUES (:quantity, :price, :product_id, :order_id)";
                    db()->dbQuery($sql, ['quantity' => $quantity, 'price' => $price, 'product_id' => $productId, 'order_id' => $orderId]);

                    $cart[] = [
                        'price' => $price,
                        'quantity' => $quantity
                    ];
                }

                foreach ($cart as $item) {
                    $total += $item['price'] * $item['quantity'];
                }

                $sql = "UPDATE " . $this->getTableName() . " SET `total` = :total WHERE `id` = :id";
                $query = db()->dbQuery($sql, ['total' => $total, 'id' => $orderId]);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
