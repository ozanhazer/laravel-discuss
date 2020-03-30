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

    public function myParticipation()
    {
        if (!auth()->check()) {
            return redirect()->route('discuss.index');
        }

        $threads = Thread::query()
            ->with('author', 'category')
            ->where('user_id', auth()->user()->id)
            ->orWhereExists(function ($query) {
                $query->select(\DB::raw(1))
                    ->from(discuss_table('posts'))
                    ->whereRaw(discuss_table('posts') . '.thread_id = ' . discuss_table('threads') . '.id')
                    ->where(discuss_table('posts') . '.user_id', auth()->user()->id);
            })
            ->orderBy('last_post_at', 'desc')
            ->paginate();

        return view('discuss::my-participation', compact('threads'));
    }
}
