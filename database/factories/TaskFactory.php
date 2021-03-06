<?php

use App\Project;
use App\Task;
use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'project_id' => factory(Project::class),
        'completed' => false,
        'body' => $faker->sentence()
    ];
});
