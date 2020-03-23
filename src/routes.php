<?php

Route::bind('user', function ($value) {
    /** @var \Illuminate\Foundation\Auth\User $userModel */
    $userClassName = config('discussions.user_model');
    $userModel     = new $userClassName;
    return $userModel->query()->where($userModel->getRouteKeyName(), $value)->firstOrFail();
});

Route::middleware('web')
    ->namespace('Alfatron\Discussions\Http\Controllers')
    ->prefix(config('discussions.route_prefix', 'discussions'))
    ->group(function () {

        Route::get('/', 'IndexController')->name('discussions.index');
        Route::get('/detail/{thread}', 'DetailController')->name('discussions.detail');

        // It means the profile page is not wanted if route is empty in the config.
        if (config('discussions.profile_route')) {
            Route::get('/user/{user}', 'UserController')->name('discussions.user');
        }

    });
