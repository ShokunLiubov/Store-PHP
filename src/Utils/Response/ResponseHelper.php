<?php

use App\Core\Response\Response;
use App\Twig\Twig;
function response(): Response
{
    $twig = (new Twig())->twig;

    return new Response($twig);
}
