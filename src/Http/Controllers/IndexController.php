<?php

namespace Alfatron\Discuss\Http\Controllers;

use Alfatron\Discuss\Models\Category;
use Alfatron\Discuss\Models\Thread;

class IndexController
{

    public function __invoke(?Category $selectedCategory)
    {
        $threads = Thread::query()
            ->with('author', 'category')
            ->orderBy('last_post_at', 'desc');

        if ($selectedCategory->exists) {
            $threads->where('category_id', $selectedCategory->id);
        }

        $threads = $threads->paginate(20);

        return view('discuss::index', compact('threads'));
    }
}
