<?php

namespace App\Seeding;

use Exception;
use Faker\Factory as FakerFactory;

class OrderSeeding extends AbstractSeeding
{
    public string $table = 'orders';

    public function seed(): void
    {
        $faker = FakerFactory::create();

        for ($i = 1; $i <= $this->count; $i++) {
            $cart = [];
            $total = 0;

            //order
            $userId = $this->query()
                ->table('user')
                ->select('id')
                ->orderBy('random')
                ->limit(1)
                ->getOne();

            $data = $this->query()
                ->table($this->table)
                ->select('first_name, last_name, address')
                ->where('user_id', '=', 'id', $userId['id'])
                ->orderBy('id', 'desc')
                ->getOne();

            if (empty($data)) {
                $data = [
                    'first_name' => $faker->firstName(),
                    'last_name' => $faker->lastName(),
                    'address' => $faker->address()
                ];
            }

            $deliveryId = $this->query()
                ->table('delivery')
                ->select('id')
                ->orderBy('random')
                ->limit(1)
                ->getOne();

            $data['delivery_id'] = $deliveryId['id'];
            $data['user_id'] = $userId['id'];
            $data['total'] = $total;

            $orderId = $this->query()
                ->table($this->table)
                ->insert($data)
                ->insertGetId();

            //order item
            for ($j = 1; $j <= rand(1, 5); $j++) {

                $product = $this->query()
                    ->table('product')
                    ->select()
                    ->orderBy('random')
                    ->limit(1)
                    ->getOne();

                $price = $product['price'];
                $quantity = $faker->numberBetween(1, 3);
                $exist = false;

                foreach ($cart as $item) {
                    if ($item['id'] === $product['id']) {
                        $exist = true;
                    }
                }

                if ($exist) {
                    continue;
                }

                $this->query()
                    ->table('order_item')
                    ->insert(['quantity' => $quantity, 'price' => $price, 'product_id' => $product['id'], 'order_id' => $orderId])
                    ->insertGetId();

                $cart[] = [
                    'id' => $product['id'],
                    'price' => $price,
                    'quantity' => $quantity
                ];
            }

            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            $this->query()
                ->table($this->table)
                ->update(['total' => $total])
                ->where('id', '=', 'id', $orderId)
                ->get();
        }
    }
}
