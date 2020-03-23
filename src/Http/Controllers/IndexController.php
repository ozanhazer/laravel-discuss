<?php

namespace Alfatron\Discussions\Http\Controllers;

use Alfatron\Discussions\Models\Thread;

class IndexController
{

    public function __invoke()
    {
        $threads = Thread::query()
            ->with('author', 'category')
            ->orderBy('last_post_at', 'desc')
            ->paginate(20);

        return view('discussions::index', compact('threads'));
    }
}
