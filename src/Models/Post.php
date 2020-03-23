<?php

namespace Alfatron\Discussions\Models;

class Post extends BaseModel
{

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function author()
    {
        return $this->belongsTo(config('discussions.user_model'), 'user_id');
    }
}
