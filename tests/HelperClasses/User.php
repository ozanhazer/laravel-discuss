<?php

namespace Alfatron\Discuss\Tests\HelperClasses;

use Alfatron\Discuss\Traits\DiscussUser;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use DiscussUser;

    /**
     * Use this email address to be able to test http requests
     * with super admin privileges.
     */
    const SUPERADMIN_EMAIL = 'superadmin@laraveldiscuss.com';

    /**
     * Use this property to toggle super admin privilege easily
     * in the tests.
     *
     * @var bool
     */
    public $isSuperAdmin = false;

    public function isDiscussSuperAdmin()
    {
        return $this->email == self::SUPERADMIN_EMAIL ? true : $this->isSuperAdmin;
    }
}
