<?php

namespace Alfatron\Discuss\Http\Controllers;

use Alfatron\Discuss\Models\Thread;
use Illuminate\Routing\Controller;

class MyParticipationController extends Controller
{
    public function __construct()
    {
        $this->middleware(config('discuss.auth_middleware'));
    }

    public function __invoke()
    {
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
