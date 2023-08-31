<?php

use App\Core\DataBase\DataBase;
function db(): DataBase
{
    return new DataBase();
}