<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Alfatron\Discuss\Models\Category;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(\Alfatron\Discuss\Models\Thread::class, function (Faker $faker) {
    $title = $faker->sentence;

    return [
        'user_id'     => factory(config('discuss.user_model')),
        'category_id' => factory(Category::class),
        'title'       => $title,
        'slug'        => Str::slug($title),
        'body'        => $faker->text(),
        'sticky'      => $faker->boolean,
        'view_count'  => $faker->numberBetween(0, 1000),
        'post_count'  => 0,
    ];
});
