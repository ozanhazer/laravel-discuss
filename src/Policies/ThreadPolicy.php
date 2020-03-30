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
        return $thread->user_id == $user->id;
    }

    public function delete(User $user, Thread $thread)
    {
        return $thread->user_id == $user->id;
    }

    public function changeCategory(User $user, Thread $thread)
    {
        return false;
    }

    public function makeSticky(User $user, Thread $thread)
    {
        return false;
    }
}
