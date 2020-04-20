<?php

namespace Alfatron\Discuss\Discuss\UniqueChecker;

use Illuminate\Support\Facades\Redis;

class RedisStorage implements UniqueCheckerStorage
{
    public function removeExpired()
    {
        // Expiration handled by redis...
    }

    public function check(string $key)
    {
        return Redis::exists($key);
    }

    public function touch(string $key)
    {
        // Expiration in minutes
        $expiration = config('discuss.view_count.expiration');

        Redis::set($key, '1', 'EX', $expiration * 60);
    }
}
