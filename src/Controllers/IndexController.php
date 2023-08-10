<?php

namespace App\Controllers;

class IndexController
{
    public function showMainPage($twig)
    {
        echo $twig->render('MainPage/MainPage.twig');
    }
}
