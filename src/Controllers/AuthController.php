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

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService)
    {
    }

    public function registration(RegisterDTO $dto, RegistrationValidate $validator): Response
    {
        try {
            $validator->validate($dto);
            $this->authService->registration($dto);

            return response()->redirect('main');
        } catch (Exception $e) {
            $data['email'] = $dto->getEmail();
            $data['password'] = $dto->getPassword();
            $data['name'] = $dto->getName();
            $error[] = $e->getMessage();

            return response()->view('Pages/Auth/Auth', ['type' => 'register', 'error' => $error, 'data' => $data]);
        }
    }

    public function login(AuthDTO $dto, AuthValidate $validator): Response
    {
        try {
            $validator->validate($dto);
            $this->authService->login($dto);

            return response()->redirect('main');
        } catch (Exception $e) {
            $data['email'] = $dto->getEmail();
            $data['password'] = $dto->getPassword();
            $error[] = $e->getMessage();
            return response()->view('Pages/Auth/Auth', ['type' => 'login', 'error' => $error, 'data' => $data]);
        }
    }

    public function logout(): void
    {
        unset($_SESSION['auth-user']);
    }

    public function loginPage(): Response
    {
        return response()->view('Pages/Auth/Auth', ['type' => 'login']);
    }

    public function registerPage(): Response
    {
        return response()->view('Pages/Auth/Auth', ['type' => 'register']);
    }
}
