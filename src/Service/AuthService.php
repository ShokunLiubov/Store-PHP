<?php

namespace App\Service;

include_once('src/model/AuthModel.php');

use Exception;
use App\DTO\AuthDTO;
use App\DTO\RegisterDTO;
use App\Model\AuthModel;
use App\Utils\DataBase;

class AuthService
{
    public function registration(RegisterDTO $dto)
    {
        $db = new DataBase();
        $email = $dto->getEmail();
        $model = new AuthModel('user');
        $exist = $model->getByEmail($email);

        if ($exist) {
            throw new Exception('User is already exist!');
        }

        $password = $dto->getPassword();
        $name = $dto->getName();
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO `user` (`email`, `name`, `password`)
                        VALUES (?, ?, ?)";
        $db->dbQuery($sql, [$email, $name, $hashedPassword]);

        $create = $model->getByEmail($email);

        if ($create) {
            $_SESSION['auth-user'] = $create[0]['id'];
            return $create[0];
        } else {
            throw new Exception('Something error...');
        }
    }

    public function login(AuthDTO $dto)
    {
        $db = new DataBase();
        $email = $dto->getEmail();
        $model = new AuthModel('user');
        $user = $model->getByEmail($email);
        $password = $dto->getPassword();

        if (!$user) {
            throw new Exception('User not found!');
        }

        $hashedPasswordFromDatabase = $user[0]['password'];
        $validPassword = password_verify($password, $hashedPasswordFromDatabase);

        if (!$validPassword) {
            throw new Exception('Wrong email or password');
        }
        $_SESSION['auth-user'] = $user[0]['id'];
        return $user[0];
    }
}
