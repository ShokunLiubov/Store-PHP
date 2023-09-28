<?php

namespace App\Validator;

use Exception;
use App\Dto\RegisterDTO;
use Respect\Validation\Validator as v;
use App\Validator\AuthValidate;

class RegistrationValidate extends AuthValidate
{
    public function validate(RegisterDTO $dto): void
    {
        parent::validate($dto);

        if (!v::stringType()->length(5, 20)->validate($dto->getName())) {
            throw new Exception('String must contain between 5 and 20 characters!');
        }
    }
}
