<?php

namespace App\Controllers;

use Exception;
use App\Service\AuthService;
use App\Dto\AuthDTO;
use App\Dto\RegisterDTO;
use App\Validator\RegistrationValidate;

include_once('src/dto/AuthDTO.php');

class AuthController
{
    public function registration($twig)
    {
        try {
            $dto = new RegisterDTO();
            (new RegistrationValidate())->validate();
            $auth = new AuthService();

            $user = $auth->registration($dto);
            $response = ['user' => $user, 'status' => 201];

            header('Location: http://localhost/make-up');
        } catch (Exception $e) {
            $data['email'] = $dto->getEmail();
            $data['password'] = $dto->getPassword();
            $data['name'] = $dto->getName();
            $error[] = $e->getMessage();
            echo $twig->render('Auth/v_auth.twig', ['type' => 'register', 'error' => $error, 'data' => $data]);
        }
    }

    public function login($twig)
    {
        try {
            $dto = new AuthDTO();
            (new RegistrationValidate())->validate();
            $auth = new AuthService();

            $user = $auth->login($dto);
            $response = ['user' => $user, 'status' => 200];

            header('Location: http://localhost/make-up');
        } catch (Exception $e) {
            $data['email'] = $dto->getEmail();
            $data['password'] = $dto->getPassword();
            $error[] = $e->getMessage();
            echo $twig->render('Auth/v_auth.twig', ['type' => 'login', 'error' => $error, 'data' => $data]);
        }
    }

    public function logout()
    {
        unset($_SESSION['auth-user']);
    }

    public function showAuthPage($twig, string $type)
    {
        if (isset($_SESSION['auth-user'])) {
            header('Location: http://localhost/make-up');
        }
        echo $twig->render('Auth/v_auth.twig', ['type' => $type]);
    }
}
