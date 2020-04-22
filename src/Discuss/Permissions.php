<?php

namespace Alfatron\Discuss\Discuss;

use Alfatron\Discuss\Models\Post;
use Alfatron\Discuss\Models\Thread;

class Permissions
{
    public static $availablePermissions = [
        Thread::class => ['insert', 'update', 'delete', 'changeCategory', 'makeSticky'],

        Post::class => ['insert', 'update', 'delete'],

        // ... there is also edit-permission ability?
    ];

    public static $entities = [
        Thread::class,
        Post::class,
    ];
}
