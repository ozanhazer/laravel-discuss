<?php

namespace Alfatron\Discuss\Tests\ControllerTests;

use Alfatron\Discuss\Models\FollowedThread;
use Alfatron\Discuss\Models\Thread;
use Alfatron\Discuss\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Route;

class FollowedThreadsControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    function followed_threads_endpoint_works()
    {
        $this->actingAs(factory(config('discuss.user_model'))->create());

        $response = $this->get(route('discuss.followed-threads'));
        $response->assertOk();
    }

    /**
     * @test
     */
    function followed_threads_link_requires_authentication()
    {
        // By default laravel redirects to login if not authenticated
        Route::get('/login', function () {
        })->name('login');

        $response = $this->get(route('discuss.followed-threads'));
        $response->assertRedirect();
    }

    /**
     * @test
     */
    function followed_threads_page_shows_followed_threads_only()
    {
        $me = factory(config('discuss.user_model'))->create();
        $this->actingAs($me);

        $threads = factory(Thread::class, 3)->create();

        $followed = $threads[0];

        $followedThread            = new FollowedThread();
        $followedThread->user_id   = $me->id;
        $followedThread->thread_id = $followed->id;
        $followedThread->save();

        $response = $this->get(route('discuss.followed-threads'));
        $response->assertSee($followed->title);

        for ($i = 1; $i < 3; $i++) {
            $response->assertDontSee($threads[$i]->title);
        }
    }

    /**
     * @test
     */
    function follow_works_ok()
    {
        $me = factory(config('discuss.user_model'))->create();
        $this->actingAs($me);

        $thread = factory(Thread::class)->create();

        $response = $this->post(route('discuss.follow', $thread));
        $response->assertOk();
        $response->assertExactJson(['success' => true]);
    }

    /**
     * @test
     */
    function follow_does_nothing_if_already_following()
    {
        $me = factory(config('discuss.user_model'))->create();
        $this->actingAs($me);

        $thread = factory(Thread::class)->create();

        $followedThread            = new FollowedThread();
        $followedThread->user_id   = $me->id;
        $followedThread->thread_id = $thread->id;
        $followedThread->save();

        $response = $this->post(route('discuss.follow', $thread));
        $response->assertOk();
        $response->assertExactJson(['success' => true]);
    }

    /**
     * @test
     */
    function unfollow_works_ok()
    {
        $me = factory(config('discuss.user_model'))->create();
        $this->actingAs($me);

        $thread = factory(Thread::class)->create();

        $followedThread            = new FollowedThread();
        $followedThread->user_id   = $me->id;
        $followedThread->thread_id = $thread->id;
        $followedThread->save();

        $response = $this->post(route('discuss.unfollow', $thread));
        $response->assertOk();
        $response->assertExactJson(['success' => true]);
    }

    /**
     * @test
     */
    function unfollow_does_nothing_if_already_following()
    {
        $me = factory(config('discuss.user_model'))->create();
        $this->actingAs($me);

        $thread = factory(Thread::class)->create();

        $response = $this->post(route('discuss.unfollow', $thread));
        $response->assertOk();
        $response->assertExactJson(['success' => true]);
    }
}
