<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Model\AuthModel;

class TwigAuth extends AbstractExtension
{

    public function getFunctions()
    {
        return [
            new TwigFunction('isAuth', [$this, 'isAuth']),
            new TwigFunction('getAuthUser', [$this, 'getAuthUser'])
        ];
    }

    public function isAuth()
    {
        $isAuth = false;
        if (isset($_SESSION['auth-user'])) {
            $isAuth = true;
        }
        return $isAuth;
    }

    public function getAuthUser()
    {
        $authUser = null;

        if (isset($_SESSION['auth-user'])) {
            $model = new AuthModel('user');
            $id = $_SESSION['auth-user'];
            $authUser = $model->getById($id);
        }
        return $authUser;
    }
}
