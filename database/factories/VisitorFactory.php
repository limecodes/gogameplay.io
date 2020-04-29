<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Visitor;
use Faker\Generator as Faker;

$factory->define(Visitor::class, function (Faker $faker) {
    return [
        'uid' => $faker->uuid,
        'ip_address' => $faker->ipv4,
        'mobile_connection' => true
    ];
});
