<?php

namespace Alfatron\Discuss\Models;

class FollowedThread extends BaseModel
{

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function user()
    {
        return $this->belongsTo(config('discuss.user_model'), 'user_id');
    }
}
