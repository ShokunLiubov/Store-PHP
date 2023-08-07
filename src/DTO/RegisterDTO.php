<?php

namespace App\Dto;

class RegisterDTO extends AuthDTO
{

    public function getName(): string
    {
        return $_REQUEST['name'];
    }
}
