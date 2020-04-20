<?php

namespace Alfatron\Discuss\Tests\FeatureTests;

use Alfatron\Discuss\Discuss\UniqueChecker\UniqueChecker;
use Alfatron\Discuss\Events\ThreadVisited;
use Alfatron\Discuss\Listeners\UpdateViewCount;
use Alfatron\Discuss\Models\Thread;
use Alfatron\Discuss\Tests\TestCase;
use Facades\Alfatron\Discuss\Listeners\UpdateViewCount as UpdateViewCountFacade;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @see \Alfatron\Discuss\Listeners\UpdateViewCount::class
 */
class UpdateViewCountTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function thread_view_count_incremented_on_visit()
    {
        $thread = factory(Thread::class)->create(['view_count' => 5]);
        $this->get($thread->url())->assertStatus(200);
        $thread->refresh();

        $this->assertEquals(6, $thread->view_count);
    }

    /**
     * @test
     */
    public function dont_increase_visits_if_crawler()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (compatible; Sosospider/2.0; +http://help.soso.com/webspider.htm)';

        $thread = factory(Thread::class)->create(['view_count' => 5]);
        UpdateViewCountFacade::handle(new ThreadVisited($thread));
        $thread->refresh();

        $this->assertEquals(5, $thread->view_count);

        unset($_SERVER['HTTP_USER_AGENT']);
    }

    /**
     * @test
     */
    public function dont_increase_visits_if_privacy_is_requested()
    {
        $thread = factory(Thread::class)->create(['view_count' => 5]);

        $this->withHeader('dnt', '1')
            ->get($thread->url())->assertStatus(200);

        $thread->refresh();

        $this->assertEquals(5, $thread->view_count);
    }

    /**
     * @test
     */
    public function increase_visits_if_privacy_is_requested_but_user_ignores_it_by_config()
    {
        $thread = factory(Thread::class)->create(['view_count' => 5]);

        config()->set('discuss.view_count.honor_dnt', false);
        $this->withHeader('dnt', '1')
            ->get($thread->url())->assertStatus(200);

        $thread->refresh();

        $this->assertEquals(6, $thread->view_count);
    }

    /**
     * @test
     */
    public function increase_if_request_is_unique()
    {
        $thread = factory(Thread::class)->create(['view_count' => 5]);

        $uniqueChecker = $this->mock(UniqueChecker::class);
        $uniqueChecker->shouldReceive('keyExists')
            ->once()
            ->andReturn(false);

        $listener = new UpdateViewCount($uniqueChecker);
        $listener->handle(new ThreadVisited($thread));

        $thread->refresh();
        $this->assertEquals(6, $thread->view_count);
    }

    /**
     * @test
     */
    public function dont_increase_if_request_is_not_unique()
    {
        $thread = factory(Thread::class)->create(['view_count' => 5]);

        $uniqueChecker = $this->mock(UniqueChecker::class);
        $uniqueChecker->shouldReceive('keyExists')
            ->once()
            ->andReturn(true);

        $listener = new UpdateViewCount($uniqueChecker);
        $listener->handle(new ThreadVisited($thread));

        $thread->refresh();
        $this->assertEquals(5, $thread->view_count);
    }

    /**
     * @test
     * @environment-setup useInvalidRedisPort
     */
    public function handle_redis_config_error()
    {
        $thread = factory(Thread::class)->create(['view_count' => 5]);

        $response = $this->get($thread->url());
        $response->assertStatus(500);
        $response->assertSee('Connection refused');
    }

    protected function useInvalidRedisPort($app)
    {
        $app->config->set('database.redis.default.port', 5000);
    }
}
