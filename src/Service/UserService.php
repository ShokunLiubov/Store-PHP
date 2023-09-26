<?php

namespace App\Service;

use App\Model\UserModel;
use App\Service\Service;

class UserService extends Service
{

    public function __construct(protected UserModel $userModel)
    {
    }

}