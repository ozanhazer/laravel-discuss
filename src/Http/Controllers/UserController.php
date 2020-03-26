<?php


namespace Alfatron\Discuss\Http\Controllers;


class UserController
{

    public function __invoke($user)
    {
        return view('discuss::user', compact('user'));
    }
}
