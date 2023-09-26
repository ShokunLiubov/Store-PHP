<?php

namespace App\Core\Request;

class Request
{
    public static function get(string $key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }

    public static function getFilters($filtersKey): array
    {
        $filters = [];

        foreach ($filtersKey as $key) {
            if (isset($_GET[$key])) {
                $filters[$key] = $_GET[$key];
            }
        }

        return $filters;
    }
}