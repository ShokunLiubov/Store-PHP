<?php

use App\Core\DataBase\DataBase;
use App\Core\DataBase\QueryBuilder;
function db(): DataBase
{
    return new DataBase();
}

function qb(): QueryBuilder
{
    return new QueryBuilder(new DataBase());
}