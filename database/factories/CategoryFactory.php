<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Alfatron\Discussions\Models\Category;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Category::class, function (Faker $faker) {
    $name = $faker->name;
    return [
        'name'  => $name,
        'color' => $faker->hexColor,
        'slug'  => Str::slug($name),
        'order' => 1,
    ];
});
