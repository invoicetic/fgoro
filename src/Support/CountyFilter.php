<?php

namespace Invoicetic\Fgoro\Support;

class CountyFilter
{

    public static function filter($name)
    {
        $name = html_entity_decode($name, ENT_QUOTES, 'UTF-8');
        $name = static::filterDiacritics($name);
        $name = static::filterIncorectNames($name);
        $name = trim($name);
        $name = static::filterCities($name);
        return $name;
    }

    protected static function filterDiacritics($name)
    {
        $name = iconv('UTF-8', 'ASCII//TRANSLIT', $name);
        return $name;
    }

    protected static function filterCities(bool|string $name): bool|string
    {
        $cities = [
            'Ploiesti' => 'Prahova',
            'Chiajna' => 'Ilfov',
        ];
        return $cities[$name] ?? $name;
    }

    protected static function filterIncorectNames(bool|string $name)
    {
        $trans = ['Judetul' => '',];
        return strtr($name, $trans);
    }
}
