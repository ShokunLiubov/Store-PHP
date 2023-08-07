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

    public static function getAll()
    {
        $db = new DataBase();
        $sql = 'SELECT * FROM  ' . self::getTableName() . ' ORDER BY id DESC';
        $query = $db->dbQuery($sql);
        return $query->fetch();
    }

    public static function getById($id)
    {
        $db = new DataBase();
        $sql = 'SELECT * FROM ' . self::getTableName() . ' WHERE id=:id';
        $query = $db->dbQuery($sql, ['id' => $id]);
        return $query->fetch();
    }
}
