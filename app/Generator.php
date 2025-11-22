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

        $countPlayers = count($config->players);
        $heroesCount = ($countPlayers * 4) + 1;
        $heroesKeys = array_rand($heroes, $heroesCount);
        $randomHeroes = array_intersect_key($heroes, array_flip((array) $heroesKeys));

        ksort($randomHeroes);

        return $randomHeroes;
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