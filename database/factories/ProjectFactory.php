<?php

use App\User;
use Faker\Generator as Faker;

$factory->define(App\Project::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(4),
        'description' => $faker->sentence(10),
        'notes' => 'Some notes',
        'owner_id' => factory(User::class)
    ];
});
