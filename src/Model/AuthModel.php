<?php

namespace App\Model;

use App\Model\Model;
use App\Utils\DataBase;

class AuthModel extends Model
{
    public function getByEmail(string $email)
    {
        $db = new DataBase();
        $sql = 'SELECT * FROM ' . $this->getTableName() . ' WHERE email=:email';
        $query = $db->dbQuery($sql, ['email' => $email]);
        return $query->fetchAll();
    }
}
