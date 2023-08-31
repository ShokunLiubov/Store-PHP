<?php

namespace App\Twig;

use App\Twig\CustomTwigExtensions;
use App\Twig\TwigAuth;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;
class Twig
{
    public $twig;
    public function __construct() {
        $loader = new FilesystemLoader('src/Views');
        $twig = new Environment($loader, ['cache' => false, 'debug' => true]);
        $twig->addFunction(new TwigFunction('current_time', fn () => time()));
        $twig->addExtension(new DebugExtension());
        $twig->addExtension(new CustomTwigExtensions());
        $twig->addExtension(new TwigAuth());
        $this->twig = $twig;
    }

}