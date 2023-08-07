<?php

namespace App\Dto;

class AuthDTO
{
    public function getEmail(): string
    {
        return $_REQUEST['email'];
    }

    public function getPassword(): string
    {
        return $_REQUEST['email'];
    }
}
