<?php

Route::bind('user', function ($value) {
    /** @var \Illuminate\Foundation\Auth\User $userModel */
    $userClassName = config('discuss.user_model');
    $userModel     = new $userClassName;
    return $userModel->query()->where($userModel->getRouteKeyName(), $value)->firstOrFail();
});

Route::middleware(config('discuss.middleware_group'))
    ->namespace('Alfatron\Discuss\Http\Controllers')
    ->prefix(config('discuss.route_prefix'))
    ->group(function () {

        Route::get('/', 'IndexController')->name('discuss.index');
        Route::get('/detail/{category}/{thread}', 'DetailController')->name('discuss.detail');

        Route::get('/my-participation', 'MyParticipationController')->name('discuss.my-participation');

        Route::get('/followed-threads', 'FollowedThreadsController')->name('discuss.followed-threads');
        Route::post('/follow/{thread}', 'FollowedThreadsController@follow')->name('discuss.follow');
        Route::post('/unfollow/{thread}', 'FollowedThreadsController@unfollow')->name('discuss.unfollow');

        Route::get('/user/{user}', 'UserController')->name('discuss.user');

        Route::get('/{selectedCategory}', 'IndexController')->name('discuss.category');

        Route::post('/thread/create', 'ThreadController@insert')->name('discuss.thread.create');
        Route::post('/thread/update/{thread}', 'ThreadController@update')->name('discuss.thread.update');
        Route::get('/thread/populate/{thread}', 'ThreadController@populate')->name('discuss.thread.populate');
        Route::post('/thread/delete/{thread}', 'ThreadController@delete')->name('discuss.thread.delete');

        Route::post('/change-category/{thread}', 'ThreadController@changeCategory')->name('discuss.change-category');
        Route::post('/make-sticky/{thread}', 'ThreadController@makeSticky')->name('discuss.make-sticky');
        Route::post('/make-unsticky/{thread}', 'ThreadController@makeUnsticky')->name('discuss.make-unsticky');

        Route::post('/post/create/{thread}', 'PostController@insert')->name('discuss.post.create');
        Route::post('/post/update/{post}', 'PostController@update')->name('discuss.post.update');
        Route::get('/post/populate/{post}', 'PostController@populate')->name('discuss.post.populate');
        Route::post('/post/delete/{post}', 'PostController@delete')->name('discuss.post.delete');
    });
