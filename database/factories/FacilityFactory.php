<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Facility;
use Faker\Generator as Faker;

$factory->define(Facility::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->text,
        'has_image' => false,
        'corona_hash' => Str::random(6),
        'user_id' => null
    ];
});
