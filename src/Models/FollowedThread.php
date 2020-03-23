<?php

namespace Alfatron\Discussions\Models;

class FollowedThread extends BaseModel
{

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function user()
    {
        return $this->belongsTo(config('discussions.user_model'), 'user_id');
    }
}
