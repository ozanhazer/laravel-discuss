<?php

namespace Alfatron\Discuss\Tests\AuthorizationTests;

use Alfatron\Discuss\Models\Thread;
use Alfatron\Discuss\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ThreadControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    function guests_cannot_post_thread()
    {
        $response = $this->post(route('discuss.thread.create'), [], ['Accept' => 'application/json']);
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    function users_cannot_edit_others_threads()
    {
        $thread = factory(Thread::class)->create();
        $someUser = factory(config('discuss.user_model'))->create();
        $this->actingAs($someUser);

        $response = $this->post(route('discuss.thread.update', $thread), [], ['Accept' => 'application/json']);
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    function users_cannot_delete_others_threads()
    {
        $thread = factory(Thread::class)->create();
        $someUser = factory(config('discuss.user_model'))->create();
        $this->actingAs($someUser);

        $response = $this->post(route('discuss.thread.delete', $thread), [], ['Accept' => 'application/json']);
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    function standard_users_cannot_change_category()
    {
        // ...special permissions are needed for changing categories
        $thread = factory(Thread::class)->create();

        // Owner of the thread
        $this->actingAs($thread->author);

        $response = $this->post(route('discuss.change-category', $thread), [], ['Accept' => 'application/json']);
        $response->assertStatus(403);

        // Not the owner...
        $this->actingAs(factory(config('discuss.user_model'))->create());

        $response = $this->post(route('discuss.change-category', $thread), [], ['Accept' => 'application/json']);
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    function standard_users_cannot_make_sticky()
    {
        // ...special permissions are needed for making threads sticky
        $thread = factory(Thread::class)->create();

        $actions      = ['discuss.make-sticky', 'discuss.make-unsticky'];
        foreach ($actions as $action) {
            // Owner of the thread
            $this->actingAs($thread->author);

            $response = $this->post(route($action, $thread), [], ['Accept' => 'application/json']);
            $response->assertStatus(403);

            // Not the owner...
            $this->actingAs(factory(config('discuss.user_model'))->create());

            $response = $this->post(route($action, $thread), [], ['Accept' => 'application/json']);
            $response->assertStatus(403);
        }
    }
}
