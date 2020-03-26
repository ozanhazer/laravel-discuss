<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Alfatron\Discuss\Models\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'thread_id' => factory(\Alfatron\Discuss\Models\Thread::class),
        'user_id'   => factory(config('discuss.user_model')),
        'body'      => $faker->text(),
    ];
});
