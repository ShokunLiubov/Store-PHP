<?php

namespace App\Service;

include_once('src/model/UserModel.php');

use App\DTO\AuthDTO;
use App\DTO\RegisterDTO;
use App\Model\UserModel;
use Exception;

class AuthService  extends Service
{
    public function __construct(protected UserModel $authModel)
    {
    }

    /**
     * @throws Exception
     */
    public function registration(RegisterDTO $dto): void
    {
        $email = $dto->getEmail();
        $exist = $this->authModel->getByEmail($email);

        if ($exist) {
            throw new Exception('User is already exist!');
        }

        $password = $dto->getPassword();
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $name = $dto->getName();

        $createdId = $this->authModel->registration($email, $name, $hashedPassword);

        if ($createdId) {
            $_SESSION['auth-user'] = $createdId;
        } else {
            throw new Exception('Something error...');
        }
    }

    /**
     * @throws Exception
     */
    public function login(AuthDTO $dto): void
    {
        $email = $dto->getEmail();
        $password = $dto->getPassword();
        $user = $this->authModel->getByEmail($email);

        if (!$user) {
            throw new Exception('Wrong email or password!');
        }

        $hashedPasswordFromDatabase = $user['password'];
        $validPassword = password_verify($password, $hashedPasswordFromDatabase);

        if (!$validPassword) {
            throw new Exception('Wrong email or password!');
        }

        $_SESSION['auth-user'] = $user['id'];
    }
}
