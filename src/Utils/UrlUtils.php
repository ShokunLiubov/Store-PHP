<?php

namespace App\Utils;

class UrlUtils
{
    public function generateFilterUrlParams(array $filters): string
    {
        $urlParams = '';

        foreach ($filters as $key => $filter) {
            if (is_array($filter)) {
                foreach ($filter as $value) {
                    $urlParams .= "&{$key}[]=$value";
                }
            } else {
                $urlParams .= "&$key=$filter";
            }
        }

        return $urlParams;
    }

}