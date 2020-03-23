<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Alfatron\Discussions\Models\Category;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(\Alfatron\Discussions\Models\Thread::class, function (Faker $faker) {
    $title = $faker->sentence;
    return [
        'title'       => $title,
        'slug'        => Str::slug($title),
        'category_id' => factory(Category::class),
        'user_id'     => factory(config('discussions.user_model')),
        'sticky'      => $faker->boolean,
        'view_count'  => $faker->numberBetween(0, 1000),
    ];
});
