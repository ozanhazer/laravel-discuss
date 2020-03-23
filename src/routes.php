<?php
Route::middleware('web')
    ->namespace('Alfatron\Discussions\Http\Controllers')
    ->prefix(config('discussions.route_prefix', 'discussions'))
    ->group(function () {

        Route::get('/', 'IndexController')->name('discussions.index');
        Route::get('/detail/{thread}', 'DetailController')->name('discussions.detail');

    });
