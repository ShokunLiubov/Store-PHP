<?php

namespace App\Validator;

use Exception;
use App\Dto\RegisterDTO;
use Respect\Validation\Validator as v;
use App\Validator\Validate;

class RegistrationValidate extends Validate
{
    public function validate()
    {
        $dto = new RegisterDTO();
        if (!v::email()->validate($dto->getEmail())) {
            throw new Exception('Not valid email!');
        }
        if (!v::stringType()->length(5, 20)->validate($dto->getPassword())) {
            throw new Exception('String must contain between 5 and 20 characters!');
        }
        if (!v::stringType()->length(5, 20)->validate($dto->getName())) {
            throw new Exception('String must contain between 5 and 20 characters!');
        }
    }
}
