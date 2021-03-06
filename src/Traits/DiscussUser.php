<?php

namespace Alfatron\Discuss\Traits;

use Alfatron\Discuss\Models\Permission;

trait DiscussUser
{
    public function discussPermissions()
    {
        return $this->hasMany(Permission::class, 'user_id');
    }

    public function hasDiscussPermission($entity, $ability)
    {
        return $this->discussPermissions
                ->where('entity', $entity)
                ->where('ability', $ability)
                ->count() > 0;
    }

    /**
     * Only super admins are allowed to edit permissions of other users.
     */
    public function isDiscussSuperAdmin(): bool
    {
        return false;
    }

    public function discussDisplayName()
    {
        [$username, $domain] = explode('@', $this->email);

        $username = mb_strlen($username) >= 8 ?
            $username[0] . $username[1] . '****' . mb_substr($username, -1) :
            $username[0] . '****';

        $domain = $domain[0] . '****' . mb_substr($domain, -4);

        return "$username@$domain";
    }

    public function discussAvatar()
    {
        $hash = md5(mb_strtolower(trim($this->email)));

        return 'https://www.gravatar.com/avatar/' . $hash . '?d=retro';
    }
}
