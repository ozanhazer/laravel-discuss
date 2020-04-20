<?php

namespace Alfatron\Discuss\Tests\ServiceClassTests\UniqueChecker;

use Alfatron\Discuss\Discuss\UniqueChecker\RedisStorage;
use Alfatron\Discuss\Tests\TestCase;
use Illuminate\Support\Facades\Redis;

class RedisStorageTest extends TestCase
{
    /**
     * @test
     */
    public function touch_sets_the_expiration_correctly()
    {
        $expiration = 73;
        config()->set('discuss.view_count.expiration', $expiration);

        $store = new RedisStorage();
        $key   = uniqid('testing');
        $store->touch($key);

        $this->assertEquals('1', Redis::get($key));
        $this->assertEquals($expiration * 60, Redis::ttl($key));
    }
}
