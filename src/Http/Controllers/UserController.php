<?php


namespace Alfatron\Discussions\Http\Controllers;


class UserController
{

    public function __invoke($user)
    {
        return view('discussions::user', compact('user'));
    }
}
