<?php

namespace Alfatron\Discussions\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends BaseModel
{
    use SoftDeletes;

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function author()
    {
        return $this->belongsTo(config('discussions.user_model'), 'user_id');
    }
}
