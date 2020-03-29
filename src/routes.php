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
        Route::get('/detail/{category}/{thread}', 'DetailController')->name('discuss.detail');

        Route::get('/user/{user}', 'UserController')->name('discuss.user');

        Route::get('/{selectedCategory}', 'IndexController')->name('discuss.category');

        Route::post('/create-thread', 'ThreadController@insert')->name('discuss.create-thread');
        Route::post('/update-thread', 'ThreadController@update')->name('discuss.update-thread');
        Route::post('/create-post/{thread}', 'PostController@insert')->name('discuss.create-post');
        Route::post('/update-post/{thread}', 'PostController@update')->name('discuss.update-post');
    });
