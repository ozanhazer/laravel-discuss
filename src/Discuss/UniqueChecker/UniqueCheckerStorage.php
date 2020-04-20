<?php

namespace Alfatron\Discuss\Discuss\UniqueChecker;

interface UniqueCheckerStorage
{
    public function removeExpired();

    public function check(string $key);

    public function touch(string $key);
}
