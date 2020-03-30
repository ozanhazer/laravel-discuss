<?php


use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{

    public $isSuperAdmin = false;

    public function isDiscussSuperAdmin()
    {
        return $this->isSuperAdmin;
    }
}
