<?php

namespace App\Validator;

use Exception;
use App\Dto\RegisterDTO;
use Respect\Validation\Validator as v;

class AuthValidate extends Validate
{
    public function validate(): void
    {
        $dto = new RegisterDTO();
        if (!v::email()->validate($dto->getEmail())) {
            throw new Exception('Not valid email!');
        }
        if (!v::stringType()->length(5, 20)->validate($dto->getPassword())) {
            throw new Exception('String must contain between 5 and 20 characters!');
        }
    }
}
