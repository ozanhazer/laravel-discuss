<?php

namespace Alfatron\Discuss\Policies;

use Alfatron\Discuss\Models\Post;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User;

class PostPolicy
{
    use HandlesAuthorization;

    public function insert(User $user)
    {
        return true;
    }

    public function update(User $user, Post $post)
    {
        return $post->user_id == $user->id;
    }

    public function delete(User $user, Post $post)
    {
        return $post->user_id == $user->id;
    }
}
