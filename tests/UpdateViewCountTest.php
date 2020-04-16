<?php

namespace Alfatron\Discuss\Tests;

use Alfatron\Discuss\Events\ThreadVisited;
use Alfatron\Discuss\Listeners\UpdateViewCount;
use Alfatron\Discuss\Models\Thread;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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
        (new UpdateViewCount())->handle(new ThreadVisited($thread));
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

        config()->set('discuss.honor_dnt', false);
        $this->withHeader('dnt', '1')
            ->get($thread->url())->assertStatus(200);

        $thread->refresh();

        $this->assertEquals(6, $thread->view_count);
    }
}
