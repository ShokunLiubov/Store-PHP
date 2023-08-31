<?php

namespace App\Service;

include_once('src/model/AuthModel.php');

use App\DTO\AuthDTO;
use App\DTO\RegisterDTO;
use App\Model\AuthModel;
use Exception;

class AuthService
{
    public function registration(RegisterDTO $dto)
    {
        $email = $dto->getEmail();
        $model = new AuthModel();
        $exist = $model->getByEmail($email);

        if ($exist) {
            throw new Exception('User is already exist!');
        }

        $password = $dto->getPassword();
        $name = $dto->getName();
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO `user` (`email`, `name`, `password`)
                        VALUES (:email, :name, :password)";
        db()->dbQuery($sql, ['email' => $email, 'name' => $name, 'password' => $hashedPassword]);

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
