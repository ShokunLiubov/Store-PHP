<?php

namespace App\Contracts;

use App\Core\DataBase\QueryBuilder;

interface Model {
    public function query(): QueryBuilder;

    public function getTableName(): string;

    public function getById(int $id): mixed;

    public function getAll(): bool|array;
}
