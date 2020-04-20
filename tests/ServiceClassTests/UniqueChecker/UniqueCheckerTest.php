<?php

namespace Alfatron\Discuss\Tests\ServiceClassTests\UniqueChecker;

use Alfatron\Discuss\Discuss\UniqueChecker\RedisStorage;
use Alfatron\Discuss\Discuss\UniqueChecker\UniqueChecker;
use Alfatron\Discuss\Discuss\UniqueChecker\UniqueCheckerStorage;
use Alfatron\Discuss\Models\Thread;
use Alfatron\Discuss\Tests\HelperClasses\UniqueCheckerTestStorage;
use Alfatron\Discuss\Tests\TestCase;
use Illuminate\Support\Facades\Redis;

class UniqueCheckerTest extends TestCase
{
    /**
     * @test
     */
    public function default_storage_is_redis()
    {
        $storage = $this->app->get(UniqueCheckerStorage::class);
        $this->assertInstanceOf(RedisStorage::class, $storage);
    }

    /**
     * @test
     * @environment-setup changeStorage
     */
    public function storage_can_be_changed_from_config()
    {
        $storage = $this->app->get(UniqueCheckerStorage::class);
        $this->assertInstanceOf(UniqueCheckerTestStorage::class, $storage);
    }

    /**
     * @test
     */
    public function calls_storage_functions()
    {
        $storage = $this->mock(RedisStorage::class);
        $storage->shouldReceive('removeExpired', 'touch')->once();
        $storage->shouldReceive('check')->once()->andReturn(true);

        $returnVal = (new UniqueChecker($storage))->keyExists(1);
        $this->assertEquals(true, $returnVal);
    }

    /**
     * @test
     */
    public function key_exists_returns_false_if_storage_returns_false()
    {
        $storage = $this->mock(RedisStorage::class);
        $storage->shouldReceive('removeExpired', 'touch')->once();
        $storage->shouldReceive('check')->once()->andReturn(false);

        $returnVal = (new UniqueChecker($storage))->keyExists(1);
        $this->assertEquals(false, $returnVal);
    }

    /**
     * @test
     */
    public function dont_count_consequent_visits_with_redis_storage()
    {
        $thread = factory(Thread::class)->create(['view_count' => 5]);

        foreach (range(1, 5) as $i) {
            $this->get($thread->url())->assertStatus(200);
        }

        $thread->refresh();

        $this->assertEquals(6, $thread->view_count);
        $this->assertCount(1, Redis::keys($thread->id . ':*'));
    }

    /**
     * @test
     */
    public function count_if_ip_address_is_different()
    {
        $thread = factory(Thread::class)->create(['view_count' => 5]);

        $this->get($thread->url(), ['REMOTE_ADDR' => '10.1.0.1'])->assertStatus(200);
        $this->get($thread->url(), ['REMOTE_ADDR' => '10.1.0.2'])->assertStatus(200);
        $this->get($thread->url(), ['REMOTE_ADDR' => '10.1.0.3'])->assertStatus(200);
        $this->get($thread->url(), ['REMOTE_ADDR' => '10.1.0.3'])->assertStatus(200);

        $thread->refresh();

        $this->assertEquals(8, $thread->view_count);
        $this->assertCount(3, Redis::keys($thread->id . ':*'));
    }

    /**
     * @test
     */
    public function count_if_user_agent_is_different()
    {
        $thread = factory(Thread::class)->create(['view_count' => 5]);

        $this->get($thread->url(), ['REMOTE_ADDR' => '10.1.0.1', 'HTTP_USER_AGENT' => 'some agent'])->assertStatus(200);
        $this->get($thread->url(), ['REMOTE_ADDR' => '10.1.0.1', 'HTTP_USER_AGENT' => 'some agent'])->assertStatus(200);
        $this->get($thread->url(), ['REMOTE_ADDR' => '10.1.0.1', 'HTTP_USER_AGENT' => 'some other agent'])->assertStatus(200);

        $thread->refresh();

        $this->assertEquals(7, $thread->view_count);
        $this->assertCount(2, Redis::keys($thread->id . ':*'));
    }

    protected function changeStorage($app)
    {
        $app->config->set('discuss.view_count.storage', UniqueCheckerTestStorage::class);
    }
}
