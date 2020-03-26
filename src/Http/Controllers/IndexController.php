<?php

namespace Alfatron\Discuss\Http\Controllers;

use Alfatron\Discuss\Models\Thread;

class IndexController
{

    public function __invoke()
    {
        $threads = Thread::query()
            ->with('author', 'category')
            ->orderBy('last_post_at', 'desc')
            ->paginate(20);

        return view('discuss::index', compact('threads'));
    }
}
