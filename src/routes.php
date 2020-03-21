<?php
Route::middleware('web')
    ->namespace('Alfatron\Discussions\Http\Controllers')
    ->prefix(config('discussions.route_prefix', 'discussions'))
    ->group(function () {

        Route::get('/', 'IndexController')->name('discussion.index');
        Route::get('/detail', 'DetailController')->name('discussion.detail');

    });
