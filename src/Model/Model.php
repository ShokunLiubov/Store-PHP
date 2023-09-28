<?php

namespace App\Model;

use App\Core\DataBase\QueryBuilder;
use PDO;
use App\Contracts\Model as IModel;


class Model implements IModel
{
    protected string $table;
    const DEFAULT_LIMIT = 10;

    public function __construct(protected QueryBuilder $builder)
    {
        $builder->table($this->getTableName());
    }

    public function query(): QueryBuilder
    {
        return $this->builder;
    }

    public function getTableName(): string
    {
        return $this->table;
    }

    public function getAll(): bool|array
    {
        return $this->query()
            ->select()
            ->orderBy('id', 'DESC')
            ->get();
    }

    public function getById(int $id): mixed
    {
        return $this->query()
            ->select()
            ->where('id', '=', 'id', $id)
            ->getOne();
    }
}
