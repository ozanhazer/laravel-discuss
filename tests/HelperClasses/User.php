<?php

namespace Alfatron\Discuss\Tests\HelperClasses;

use Alfatron\Discuss\Traits\DiscussUser;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use DiscussUser;

    public $isSuperAdmin = false;

    public function isDiscussSuperAdmin()
    {
        return $this->isSuperAdmin;
    }
}
