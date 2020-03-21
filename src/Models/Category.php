<?php

namespace Alfatron\Discussions\Models;

class Category extends BaseModel
{

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }
}
