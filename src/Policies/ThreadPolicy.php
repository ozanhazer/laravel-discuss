<?php

namespace Alfatron\Discuss\Policies;

use Alfatron\Discuss\Models\Thread;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User;

class ThreadPolicy
{
    use HandlesAuthorization;

    public function insert(User $user)
    {
        return true;
    }

    public function update(User $user, Thread $thread)
    {
        return $thread->user_id == $user->id ||
            $user->hasDiscussPermission(Thread::class, 'update');
    }

    public function delete(User $user, Thread $thread)
    {
        return $thread->user_id == $user->id ||
            $user->hasDiscussPermission(Thread::class, 'delete');
    }

    public function changeCategory(User $user, Thread $thread)
    {
        return $user->hasDiscussPermission(Thread::class, 'changeCategory');
    }

    public function makeSticky(User $user, Thread $thread)
    {
        return $user->hasDiscussPermission(Thread::class, 'makeSticky');
    }
}
