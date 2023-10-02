<?php

use App\Core\DataBase\DataBase;
use App\Core\DataBase\QueryBuilder;
use App\Seeding\Seeding;
use App\Seeding\UserSeeding;
use App\Seeding\CategorySeeding;
use App\Seeding\ProductSeeding;
use App\Seeding\OrderSeeding;
use App\Seeding\OrderItemSeeding;
use App\Seeding\ProductCategorySeeding;

//$builder = new QueryBuilder(new DataBase());

// (new Seeding([
//     new OrderItemSeeding(1, $builder),
//     new OrderSeeding(30, $builder),
//     new ProductCategorySeeding(23, $builder),
//     new UserSeeding(10, $builder),
//     new CategorySeeding(10, $builder),
//     new ProductSeeding(30, $builder)],
//     $builder ))->remote();
//
//(new Seeding([
//    new UserSeeding(10, $builder),
//    new CategorySeeding(10, $builder),
//    new ProductSeeding(30, $builder),
//    new OrderSeeding(30, $builder)],
//    $builder))->seed();

