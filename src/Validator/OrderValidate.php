<?php

namespace App\Validator;

use App\DTO\OrderDTO;
use App\Validator\Validate;

use Exception;
use Respect\Validation\Validator as v;

class OrderValidate extends Validate
{
    /**
     * @throws Exception
     */
    public function validate(OrderDTO $dto): void
    {

        if (!v::number()->notEmpty()->validate($dto->getDelivery())) {
            throw new Exception('Delivery not choose!');
        }

        if (!v::stringType()->length(5, 30)->validate($dto->getFirstName())) {
            throw new Exception('First Name must contain between 5 and 30 characters!');
        }

        if (!v::stringType()->length(5, 30)->validate($dto->getLastName())) {
            throw new Exception('Last Name must contain between 5 and 30 characters!');
        }

        if (!v::stringType()->length(5, 40)->validate($dto->getAddress())) {
            throw new Exception('Address must contain between 5 and 40 characters!');
        }
    }

}