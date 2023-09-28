<?php

namespace App\Seeding;

use App\Core\DataBase\DataBase;
use Exception;

class Seeding
{
    private array $seedClasses = [];

    public function __construct($seedClasses)
    {
        $this->seedClasses = $seedClasses;
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
        $db = new DataBase();
        $sql = "DELETE FROM $table";
        $db->dbQuery($sql);
    }
}
