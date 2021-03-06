<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Todo;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Todo::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->sentence(3),
        'done' => $faker->numberBetween(0,1),
        'creator_id' => $faker->numberBetween(1,2),
        'affectedTo_id' => $faker->numberBetween(1,2),
        'affectedBy_id' => $faker->numberBetween(1,2),
        
    ];
});
