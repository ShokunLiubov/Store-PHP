<?php

namespace App\Controllers;

use Exception;
use App\Service\AuthService;
use App\Dto\AuthDTO;
use App\Dto\RegisterDTO;
use App\Validator\RegistrationValidate;
use App\Validator\AuthValidate;
use App\Core\Response\Response;

include_once('src/dto/AuthDTO.php');

class AuthController
{
    public function __construct(protected AuthService $authService)
    {
    }

    public function registration(): Response
    {
        try {
            $dto = new RegisterDTO();
            (new RegistrationValidate())->validate();

            $user = $this->authService->registration($dto);

            response()->redirect('http://localhost/make-up/main?page=1');
        } catch (Exception $e) {
            $data['email'] = $dto->getEmail();
            $data['password'] = $dto->getPassword();
            $data['name'] = $dto->getName();
            $error[] = $e->getMessage();
            return response()->view('Auth/Auth', ['type' => 'register', 'error' => $error, 'data' => $data]);
        }
    }

    public function login(): Response
    {
        try {
            $dto = new AuthDTO();
            (new AuthValidate())->validate();
            $auth = new AuthService();

            $user = $this->authService->login($dto);

            response()->redirect('http://localhost/make-up/main?page=1');
        } catch (Exception $e) {
            $data['email'] = $dto->getEmail();
            $data['password'] = $dto->getPassword();
            $error[] = $e->getMessage();
            return response()->view('Auth/Auth', ['type' => 'login', 'error' => $error, 'data' => $data]);
        }
    }

    public function logout(): void
    {
        unset($_SESSION['auth-user']);
    }

    public function showAuthPage(string $type): Response
    {
        if (isset($_SESSION['auth-user'])) {
            return response()->redirect('http://localhost/make-up/main?page=1');
        }

        return response()->view('Auth/Auth', ['type' => $type]);
    }
}
