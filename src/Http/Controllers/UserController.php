<?php

namespace Alfatron\Discuss\Http\Controllers;

class UserController
{

    public function __invoke($user)
    {
        if (!config('discuss.profile_route')) {
            abort(404);
        }

        return view('discuss::user', compact('user'));
    }
}
