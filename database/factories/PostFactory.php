<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Alfatron\Discussions\Models\Post;
use Faker\Generator as Faker;
use Illuminate\Foundation\Auth\User;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'thread_id' => factory(\Alfatron\Discussions\Models\Thread::class),
        'user_id'   => factory(User::class),
        'body'      => $faker->text(),
    ];
});
