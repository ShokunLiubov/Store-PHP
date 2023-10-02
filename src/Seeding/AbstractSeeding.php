<?php

namespace App\Seeding;

use App\Core\DataBase\QueryBuilder;

abstract class AbstractSeeding
{
    protected QueryBuilder $builder;

    public string $table;

    public function __construct(public int $count, QueryBuilder $builder)
    {
        $this->builder = $builder;

        $this->builder->table($this->getTableName());
    }

    public function query(): QueryBuilder
    {
        return $this->builder;
    }

    public function getTableName(): string
    {
        return $this->table;
    }

    abstract public function seed(): void;
}
