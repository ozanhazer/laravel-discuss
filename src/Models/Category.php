<?php

namespace Alfatron\Discuss\Models;

class Category extends BaseModel
{
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
