<?php

namespace App\Seeding;

use App\Utils\DataBase;

// (new Seeding([new UserSeed($count), new ProductSeed()]))->refresh(10);

class Seeding
{
    private array $seedClasses = [];

    public function __construct($seedClasses)
    {
        $this->seedClasses = $seedClasses;
    }

    public function refresh()
    {
        foreach ($this->seedClasses as $cl) {
            $this->clearTable($cl->getTableName());
            $cl->seed();
        }
    }

    public function seed()
    {
        foreach ($this->seedClasses as $cl) {
            $cl->seed();
        }
    }

    public function remote()
    {
        foreach ($this->seedClasses as $cl) {
            $this->clearTable($cl->getTableName());
        }
    }

    public function clearTable(string $table)
    {
        $db = new DataBase();
        $sql = "DELETE FROM $table";
        $db->dbQuery($sql);
    }
}
