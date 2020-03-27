<?php


namespace Alfatron\Discuss\Http\Controllers;


use Alfatron\Discuss\Models\Category;
use Alfatron\Discuss\Models\Thread;

class DetailController
{

    public function __invoke(Category $category, Thread $thread)
    {
        if ($thread->category_id != $category->id) {
            abort(404);
        }

        return view('discuss::detail', compact('thread'));
    }
}
