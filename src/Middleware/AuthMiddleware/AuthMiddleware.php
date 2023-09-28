<?php

namespace App\Middleware\AuthMiddleware;

use App\Core\Response\Response;

class AuthMiddleware
{
 public function isAuth() : Response | null
 {
     if (isset($_SESSION['auth-user'])) {
         return response()->redirect('main');
     }

     return null;
 }

    public function onlyAuth(): Response | null
    {

        if (!isset($_SESSION['auth-user'])) {
            return response()->redirect('auth/login');
        }

        return null;
    }
}