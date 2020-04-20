<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Alfatron\Discuss\Models\Permission;
use Faker\Generator as Faker;

$factory->define(Permission::class, function (Faker $faker) {
    $perm = $faker->randomElement(\Alfatron\Discuss\Discuss\Permission::$permissions);

    return [
        'user_id'    => factory(config('discuss.user_model')),
        'entity'     => $perm[0],
        'ability'    => $perm[1],
        'granted_by' => factory(config('discuss.user_model')),
    ];
});
