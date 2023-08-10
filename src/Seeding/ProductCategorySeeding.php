<?php

namespace App\Seeding;

use App\Utils\DataBase;
use App\Seeding\AbstractSeed;
use Faker\Factory as FakerFactory;
use Exception;

class ProductCategorySeeding extends AbstractSeed
{
    public string $table = 'product_category';

    public function seed()
    {
    }
}
