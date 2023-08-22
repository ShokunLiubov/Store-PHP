<?php

namespace App\Service;

include_once('src/model/AuthModel.php');

use App\Model\ProductModel;
use Exception;
use App\DTO\AuthDTO;
use App\DTO\RegisterDTO;
use App\Model\AuthModel;
use App\Utils\DataBase;

class ProductService
{
    public function getProduct(int $id)
    {
        $model = new ProductModel('product');
        return $model->getById($id);
    }
}
