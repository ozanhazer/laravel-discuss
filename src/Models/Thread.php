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
}
