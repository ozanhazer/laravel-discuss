<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Alfatron\Discuss\Discuss\Permissions;
use Alfatron\Discuss\Models\Permission;
use Faker\Generator as Faker;

$factory->define(Permission::class, function (Faker $faker) {
    $entity = $faker->randomElement(Permissions::$entities);

    return [
        'user_id'    => factory(config('discuss.user_model')),
        'entity'     => $entity,
        'ability'    => $faker->randomElement(Permissions::$availablePermissions[$entity]),
        'granted_by' => factory(config('discuss.user_model')),
    ];
});
