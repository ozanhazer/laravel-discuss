<?php

namespace Alfatron\Discuss\Http\Controllers;

use Alfatron\Discuss\Models\Post;
use Alfatron\Discuss\Models\Thread;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class PostController
{

    use ValidatesRequests;

    public function insert(Thread $thread, Request $request)
    {
        $this->validate($request, [
            'body' => 'required',
        ]);

        $post            = new Post();
        $post->user_id   = $request->user()->id;
        $post->thread_id = $thread->id;
        $post->body      = $request->get('body');
        $post->save();

        return response()->json(['success' => true]);
    }
}
