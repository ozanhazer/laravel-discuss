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

        Route::post('/create-thread', 'ThreadController@insert')->name('discuss.thread.create');
        Route::post('/update-thread/{thread}', 'ThreadController@update')->name('discuss.thread.update');
        Route::get('/populate-thread/{thread}', 'ThreadController@populate')->name('discuss.thread.populate');
        Route::post('/delete-thread/{thread}', 'ThreadController@delete')->name('discuss.thread.delete');

        Route::post('/change-category/{thread}', 'ThreadController@changeCategory')->name('discuss.change-category');
        Route::post('/make-sticky/{thread}', 'ThreadController@makeSticky')->name('discuss.make-sticky');
        Route::post('/make-unsticky/{thread}', 'ThreadController@makeUnsticky')->name('discuss.make-unsticky');

        Route::post('/create-post/{thread}', 'PostController@insert')->name('discuss.post.create');
        Route::post('/update-post/{post}', 'PostController@update')->name('discuss.post.update');
        Route::post('/delete-post/{post}', 'PostController@delete')->name('discuss.post.delete');
    });
