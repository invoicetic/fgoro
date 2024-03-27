<?php

namespace Invoicetic\Fgoro\Support;

class CountyFilter
{

    public static function filter($name) {
        $name = static::filterDiacritics($name);
        return $name;
    }

    protected static function filterDiacritics($name) {
        $name = iconv('UTF-8', 'ASCII//TRANSLIT', $name);
        return $name;
    }
}
