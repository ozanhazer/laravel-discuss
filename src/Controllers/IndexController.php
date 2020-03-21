<?php


namespace Alfatron\Discussions\Controllers;


class IndexController
{

    public function __invoke()
    {
        return view('discussions::index');
    }
}
