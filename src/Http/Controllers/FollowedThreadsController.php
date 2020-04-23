<?php

namespace Alfatron\Discuss\Http\Controllers;

use Alfatron\Discuss\Models\FollowedThread;
use Alfatron\Discuss\Models\Thread;
use DB;
use Illuminate\Routing\Controller;

class FollowedThreadsController extends Controller
{
    public function __construct()
    {
        $this->middleware(config('discuss.auth_middleware'));
    }

    public function __invoke()
    {
        $threads = Thread::query()
            ->whereExists(function ($query) {
                $followedThreads = (new FollowedThread())->getTable();
                $threads         = (new Thread())->getTable();

                $query->select(DB::raw(1))
                    ->from($followedThreads)
                    ->whereRaw($followedThreads . '.thread_id=' . $threads . '.id')
                    ->where('user_id', auth()->user()->id);
            })
            ->orderBy('last_post_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate();

        return view('discuss::followed-threads', compact('threads'));
    }

    public function follow(Thread $thread)
    {
        if (!$thread->isFollowed()) {
            $followedThread            = new FollowedThread();
            $followedThread->user_id   = auth()->user()->id;
            $followedThread->thread_id = $thread->id;
            $followedThread->save();
        }

        return response()->json(['success' => true]);
    }

    public function unfollow(Thread $thread)
    {
        $followedThread = FollowedThread::query()
            ->where('user_id', auth()->user()->id)
            ->where('thread_id', $thread->id)
            ->first();

        if ($followedThread) {
            $followedThread->delete();
        }

        return response()->json(['success' => true]);
    }
}
