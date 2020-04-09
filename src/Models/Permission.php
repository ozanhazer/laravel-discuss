<?php

namespace Alfatron\Discuss\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends BaseModel
{

    public function user()
    {
        return $this->belongsTo(config('discuss.user_model'), 'user_id');
    }

    public function grantor()
    {
        return $this->belongsTo(config('discuss.user_model'), 'granted_by');
    }
}
