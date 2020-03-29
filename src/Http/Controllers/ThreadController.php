<?php

namespace Alfatron\Discuss\Http\Controllers;

use Alfatron\Discuss\Models\Thread;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class ThreadController
{

    use ValidatesRequests;

    public function insert(Request $request)
    {
        $this->validate($request, [
            'title'       => 'required|min:10|unique:' . discuss_table('threads') . ',title',
            'category_id' => 'required|exists:' . discuss_table('categories') . ',id',
            'body'        => 'required',
        ]);

        $thread              = new Thread();
        $thread->user_id     = $request->user()->id;
        $thread->title       = $request->get('title');
        $thread->body        = $request->get('body');
        $thread->category_id = $request->get('category_id');
        $thread->save();

        return response()->json([
            'success' => true,
            'url'     => $thread->url(),
        ]);
    }
}
