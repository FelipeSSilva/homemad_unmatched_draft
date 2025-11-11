<?php

namespace App;

class GeneratorConfig
{
    public $players = [];
    public $sets = [];
    public $name;
    public $heroesAvaibleCount = 0;

    function __construct($get_values_from_request)
    {
        if ($get_values_from_request) {

            $this->players = array_filter(array_map('htmlentities', get('player', [])));

            if ((int) get('num_players') != count($this->players)) {
                return_error('Number of players does not match number of names');
            }

            $this->name = get('game_name', '');
            if (trim($this->name) == '') $this->name = 'Fight Club';
            else $this->name = htmlentities($this->name);

            $this->sets = array_filter(array_map('htmlentities', get('custom_factions', [])));
            $allHeroes = json_decode(file_get_contents('data/heroes.json'), true);

            $availableHeroes = filteringArraysByAttribute($this->sets, $allHeroes, 'sets', 'slug');
            $this->heroesAvaibleCount = count($availableHeroes);

            if($this->heroesAvaibleCount < (get('num_players') * 5)){
                return_error('The number of sets is less than required');
            }

            $this->validate();
        }
    }

    public static function fromArray(array $array): GeneratorConfig
    {
        $config = new GeneratorConfig(false);

        foreach ($array as $key => $value) {
            $config->$key = $value;
        }

        $config->validate();

        return $config;
    }

    private function validate(): void
    {
        if (count($this->players) > count(array_filter($this->players))) return_error('Some players names are not filled out');
        if (count(array_unique($this->players)) != count($this->players)) return_error('Players should all have unique names');
        if (count($this->players) < 2) return_error('Please enter at least 2 players');
    }

    public function toJson(): array
    {
        return get_object_vars($this);
    }
}
