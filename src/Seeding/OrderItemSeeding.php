<?php

namespace App\Seeding;

use App\Utils\DataBase;
use App\Seeding\AbstractSeed;
use Faker\Factory as FakerFactory;
use Exception;

class OrderItemSeeding extends AbstractSeed
{
    public string $table = 'order_item';

    public function seed()
    {
    }
}
