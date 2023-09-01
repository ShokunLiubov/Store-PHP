<?php

namespace App\Seeding;

abstract class AbstractSeed
{
    public string $table;

    public function __construct(public int $count)
    {
    }

    public function getTableName(): string
    {
        return $this->table;
    }

    abstract public function seed(): void;
}
