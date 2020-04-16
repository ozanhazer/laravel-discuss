<?php

namespace Alfatron\Discuss\Http\Controllers;

use Alfatron\Discuss\Models\Post;
use Alfatron\Discuss\Models\Thread;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class PostController
{
    use ValidatesRequests, AuthorizesRequests;

    public function insert(Thread $thread, Request $request)
    {
        $this->authorize('insert', Post::class);

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

    public function update(Post $post, Request $request)
    {
        $this->authorize('update', $post);

        $this->validate($request, [
            'body' => 'required',
        ]);

        $post->body = $request->get('body');
        $post->save();

        return response()->json(['success' => true]);
    }

    public function delete(Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();

        return response()->json(['url' => $post->thread->url()]);
    }

    public function populate(Post $post)
    {
        $this->authorize('update', $post);

        return response()->json([
            'body' => $post->body,
        ]);
    }
}
