<?php

namespace App\Model;

class AuthModel extends Model
{
    protected string $table = 'user';
    public function getByEmail(string $email): bool|array
    {
        $sql = 'SELECT * FROM ' . $this->getTableName() . ' WHERE email=:email';
        $query = db()->dbQuery($sql, ['email' => $email]);
        return $query->fetchAll();
    }
}
