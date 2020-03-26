<?php

Route::bind('user', function ($value) {
    /** @var \Illuminate\Foundation\Auth\User $userModel */
    $userClassName = config('discuss.user_model');
    $userModel     = new $userClassName;
    return $userModel->query()->where($userModel->getRouteKeyName(), $value)->firstOrFail();
});

Route::middleware('web')
    ->namespace('Alfatron\Discuss\Http\Controllers')
    ->prefix(config('discuss.route_prefix', 'discuss'))
    ->group(function () {

        Route::get('/', 'IndexController')->name('discuss.index');
        Route::get('/detail/{thread}', 'DetailController')->name('discuss.detail');

        Route::get('/user/{user}', 'UserController')->name('discuss.user');

    });
