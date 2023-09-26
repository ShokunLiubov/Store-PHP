<?php

namespace App\Model;

class UserModel extends Model
{
    protected string $table = 'user';
    public function getByEmail(string $email): bool|array
    {

        return $this->query()
            ->select()
            ->where('email', '=', 'email', $email)
            ->getOne();
    }

    public function registration(string $email, string $name, string $password)
    {

        return $this->query()
            ->insert(['email' => $email, 'name' => $name, 'password' => $password])
            ->getOne();
    }
}
