<?php

require_once 'boot.php';

$sets = json_decode(file_get_contents('data/sets.json'), true);

foreach ($sets as $set) {
    $fact = '<label class="check" for="custom_f_' . $set['slug'] . '"><input class="custom_faction" value="' . $set['slug'] . '" type="checkbox" id="custom_f_' . $set['slug'] . '" name="custom_factions[]" />';
    $fact .= '<img src="' . url('img/sets/' . $set['slug'] . '.png') . '" /> ' . $set['name'] . '</label>';
    echo $fact;
}
