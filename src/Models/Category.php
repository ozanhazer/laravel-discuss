<?php

namespace Alfatron\Discuss\Models;

use Alfatron\Discuss\Listeners\CreateSlug;

class Category extends BaseModel
{

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }
}
