<?php

namespace Alfatron\Discuss\Tests\HelperClasses;

use Alfatron\Discuss\Traits\DiscussUser;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AnotherUser extends Authenticatable
{
    use DiscussUser;

    protected $table = 'users';
}
