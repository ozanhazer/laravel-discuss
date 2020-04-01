<?php

namespace Alfatron\Discuss\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Thread extends BaseModel
{
    use SoftDeletes;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(config('discuss.user_model'), 'user_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function url()
    {
        return route('discuss.detail', ['category' => $this->category, 'thread' => $this]);
    }

    public function isFollowed()
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }

        return FollowedThread::query()
            ->where('user_id', $user->id)
            ->where('thread_id', $this->id)
            ->count() > 0;
    }
}
