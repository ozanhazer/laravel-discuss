<?php

namespace Alfatron\Discuss\Models;

class Category extends BaseModel
{

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }
}
