<?php


namespace Alfatron\Discussions\Http\Controllers;


class IndexController
{

    public function __invoke()
    {
        return view('discussions::index');
    }
}
