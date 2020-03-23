<?php


namespace Alfatron\Discussions\Http\Controllers;


use Alfatron\Discussions\Models\Thread;

class DetailController
{

    public function __invoke(Thread $thread)
    {
        return view('discussions::detail');
    }
}
