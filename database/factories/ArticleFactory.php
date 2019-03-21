<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Article::class, function (Faker $faker) {
    return [
       'user_id' => rand(1,10),
       'type_id' => rand(1,5),
       'title' => $faker->sentence,
       'content' => $faker->paragraph,
    ];
});
