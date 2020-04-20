<?php

namespace Alfatron\Discuss\Tests\HelperClasses;

class UniqueCheckerTestStorage implements \Alfatron\Discuss\Discuss\UniqueChecker\UniqueCheckerStorage
{
    public function removeExpired()
    {
    }

    public function check(string $key)
    {
    }

    public function touch(string $key)
    {
    }
}
