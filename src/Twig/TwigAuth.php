<?php

namespace App\Twig;

use App\Core\DataBase\DataBase;
use App\Core\DataBase\QueryBuilder;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Model\UserModel;

class TwigAuth extends AbstractExtension
{

    public function getFunctions(): array
    {
        return [
            new TwigFunction('isAuth', [$this, 'isAuth']),
            new TwigFunction('getAuthUser', [$this, 'getAuthUser'])
        ];
    }

    public function isAuth(): bool
    {
        $isAuth = false;

        if (isset($_SESSION['auth-user'])) {
            $isAuth = true;
        }

        return $isAuth;
    }

    public function getAuthUser(): array | null
    {
        $authUser = null;

        if (isset($_SESSION['auth-user'])) {
            $model = new UserModel(new QueryBuilder(new DataBase()));
            $id = $_SESSION['auth-user'];
            $authUser = $model->getById($id);
        }

        return $authUser;
    }
}
