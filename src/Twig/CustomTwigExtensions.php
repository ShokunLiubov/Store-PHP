<?php

namespace App\Twig;

class CustomTwigExtensions extends \Twig\Extension\AbstractExtension
{
    public function getFilters()
    {
        return [
            new \Twig\TwigFilter('ucwords', [$this, 'ucwordsFilter']),
        ];
    }

    public function ucwordsFilter($value)
    {
        return ucwords($value);
    }
}
