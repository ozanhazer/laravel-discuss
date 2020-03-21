<?php


namespace Alfatron\Discussions\Http\Controllers;


class DetailController
{

    public function __invoke()
    {
        return view('discussions::detail');
    }
}
