<?php


namespace Alfatron\Discuss\Http\Controllers;


use Alfatron\Discuss\Models\Thread;

class DetailController
{

    public function __invoke(Thread $thread)
    {
        return view('discuss::detail');
    }
}
