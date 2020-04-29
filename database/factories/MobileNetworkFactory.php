<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\MobileNetwork;
use Faker\Generator as Faker;

$factory->define(MobileNetwork::class, function (Faker $faker) {
    return [
        'name' => $faker->words(1, true)
    ];
});
