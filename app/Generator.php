<?php

namespace App;

class Generator
{
    public static function heroes($config){
        $heroes = filteringArraysByAttribute(
            $config->sets,
            self::importHeroesData(),
            'sets',
            'slug'
        );

        ksort($heroes);

        return $heroes;
    }

    public static function maps($config){
        $maps =  filteringArraysByAttribute(
            $config->sets,
            self::importMapsData(),
            'sets',
            'slug'
        );

        ksort($maps);

        return $maps;
    }

    private static function importHeroesData()
    {
        return json_decode(file_get_contents('data/heroes.json'), true);
    }

    private static function importMapsData()
    {
        return json_decode(file_get_contents('data/maps.json'), true);
    }
}