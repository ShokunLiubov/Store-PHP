<?php

namespace App\Model;

class AuthModel extends Model
{
    protected string $table = 'user';
    public function getByEmail(string $email): bool|array
    {
        $query = qb()::table($this->getTableName())
            ->where('email', '=', 'email', $email)
            ->get();

        return $query->fetch();
    }
}
