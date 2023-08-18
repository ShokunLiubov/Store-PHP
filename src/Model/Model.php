<?php

namespace App\Model;

use App\Utils\DataBase;

class Model
{
    public $table;

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    public function getTableName()
    {
        return $this->table;
    }

    public function getAll()
    {
        $db = new DataBase();
        $sql = 'SELECT * FROM  ' . $this->getTableName() . ' ORDER BY id DESC';
        $query = $db->dbQuery($sql);
        return $query->fetchAll();
    }

    public function getById(int $id)
    {
        $db = new DataBase();
        $sql = 'SELECT * FROM ' . self::getTableName() . ' WHERE id=:id';
        $query = $db->dbQuery($sql, ['id' => $id]);
        return $query->fetch();
    }
}
