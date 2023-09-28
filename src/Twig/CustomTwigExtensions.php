<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class CustomTwigExtensions extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('ucwords', [$this, 'ucwordsFilter']),
        ];
    }

    public function ucwordsFilter($value): string
    {
        return ucwords($value);
    }
}
