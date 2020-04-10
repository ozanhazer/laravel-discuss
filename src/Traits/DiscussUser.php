<?php


namespace Alfatron\Discuss\Traits;


use Alfatron\Discuss\Models\Permission;

trait DiscussUser
{

    public function discussPermissions()
    {
        return $this->hasMany(Permission::class);
    }

    /**
     * This method is called at Gate::before so returning
     * false means no other permissions given will be taken
     * into account as well.
     * FIXME: This behaviour conflicts with the name. If superAdmin is
     *        false it should simply do nothing.
     *
     * @return null|bool
     */
    public function isDiscussSuperAdmin()
    {
        // FIXME: Tests will be written. This should work correctly at the first
        //        installation otherwise user might think the package is broken.
        //        If returned false it won't allow any action even if permissions
        //        are given individually.
        //
        // FIXME: Another option is to make this method abstract and force user
        //        to implement it on first setup.
        //
        // FIXME: Another thing to consider is, we may remove this functionality
        //        alltogether and instruct users to use Gate::before in the
        //        documentation.
        //
        return null;
    }

    public function discussDisplayName()
    {
        [$username, $domain] = explode('@', $this->email);

        $username = mb_strlen($username) >= 8 ?
            $username{0} . $username{1} . '****' . mb_substr($username, -1) :
            $username{0} . '****';

        $domain = $domain{0} . '****' . mb_substr($domain, -4);

        return "$username@$domain";
    }

    public function discussAvatar()
    {
        $hash = md5(mb_strtolower(trim($this->email)));
        return 'https://www.gravatar.com/avatar/' . $hash . '?d=retro';
    }
}
