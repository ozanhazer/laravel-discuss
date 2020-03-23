<?php

namespace Alfatron\Discussions\Models;

class Thread extends BaseModel
{

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(config('discussions.user_model'), 'user_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
