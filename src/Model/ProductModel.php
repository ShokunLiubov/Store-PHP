<?php

namespace App\Model;

use App\Service\FilterService;
use App\Utils\UrlUtils;
use PDO;

class ProductModel extends Model
{
    protected string $table = 'product';

    public function categories()
    {
//        Product::query()->with(['categories'])->pagenate()
//        $this->query()->innerJoin()
    }

}

