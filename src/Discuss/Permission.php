<?php

namespace Alfatron\Discuss\Discuss;

use Alfatron\Discuss\Models\Post;
use Alfatron\Discuss\Models\Thread;

class Permission
{
    public static $permissions = [
        [Thread::class, 'insert'],
        [Thread::class, 'update'],
        [Thread::class, 'delete'],
        [Thread::class, 'changeCategory'],
        [Thread::class, 'makeSticky'],

        [Post::class, 'insert'],
        [Post::class, 'update'],
        [Post::class, 'delete'],
    ];
}
