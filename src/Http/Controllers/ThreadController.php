<?php

namespace Alfatron\Discuss\Http\Controllers;

use Alfatron\Discuss\Models\Thread;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class ThreadController
{

    use ValidatesRequests, AuthorizesRequests;

    public function insert(Request $request)
    {
        $this->authorize('insert', Thread::class);

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

    public function update(Thread $thread, Request $request)
    {
        $this->authorize('update', $thread);

        $this->validate($request, [
            'title' => 'required|min:10|unique:' . discuss_table('threads') . ',title',
            'body'  => 'required',
        ]);

        $thread->title = $request->get('title');
        $thread->body  = $request->get('body');
        $thread->save();

        return response()->json(['success' => true]);
    }

    public function delete(Thread $thread)
    {
        $this->authorize('delete', $thread);

        $thread->delete();

        return response()->json(['success' => true]);
    }

    public function changeCategory(Thread $thread, Request $request)
    {
        $this->authorize('change-category', $thread);

        $this->validate($request, [
            'category_id' => 'required|exists:' . discuss_table('categories') . ',id',
        ]);

        $thread->category_id = $request->get('category_id');
        $thread->save();

        return response()->json(['success' => true]);
    }

    public function makeSticky(Thread $thread)
    {
        $this->authorize('make-sticky', $thread);

        $thread->sticky = true;
        $thread->save();

        return response()->json(['success' => true]);
    }

    public function makeUnsticky(Thread $thread)
    {
        $this->authorize('make-sticky', $thread);

        $thread->sticky = true;
        $thread->save();

        return response()->json(['success' => true]);
    }
}
