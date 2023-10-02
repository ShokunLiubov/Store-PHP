<?php

namespace App\Seeding;

use App\Core\DataBase\QueryBuilder;
use Exception;

class Seeding
{
    protected QueryBuilder $builder;
    private array $seedClasses;


    public function __construct(array $seedClasses, QueryBuilder $builder)
    {
        $this->builder = $builder;
        $this->seedClasses = $seedClasses;
    }

    public function query(): QueryBuilder
    {
        return $this->builder;
    }

    public function refresh(): void
    {
        try {
            foreach ($this->seedClasses as $cl) {
                $this->clearTable($cl->getTableName());
                $cl->seed();
            }
        } catch(Exception $e) {
            $error = $e->getMessage();
            response()->view('Errors/Error', ['error' => $error]);
        }
    }

    public function seed(): void
    {
        try {
            foreach ($this->seedClasses as $cl) {
                $cl->seed();
            }
        } catch(Exception $e) {
            $error = $e->getMessage();
            response()->view('Errors/Error', ['error' => $error]);
        }
    }

    public function remote(): void
    {
        foreach ($this->seedClasses as $cl) {
            $this->clearTable($cl->getTableName());
        }
    }

    public function clearTable(string $table): void
    {
        $this->query()
            ->table($table)
            ->delete()
            ->get();
    }
}
