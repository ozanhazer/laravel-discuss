<?php

namespace Alfatron\Discuss\Tests\AuthorizationTests;

use Alfatron\Discuss\Models\Post;
use Alfatron\Discuss\Models\Thread;
use Alfatron\Discuss\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    function guests_cannot_reply()
    {
        $thread = factory(Thread::class)->create();
        $response = $this->post(route('discuss.post.create', $thread), [], ['Accept' => 'application/json']);
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    function users_cannot_edit_others_posts()
    {
        $post = factory(Post::class)->create();
        $someUser = factory(config('discuss.user_model'))->create();
        $this->actingAs($someUser);

        $response = $this->post(route('discuss.post.update', $post), [], ['Accept' => 'application/json']);
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    function users_cannot_delete_others_posts()
    {
        $post = factory(Post::class)->create();
        $someUser = factory(config('discuss.user_model'))->create();
        $this->actingAs($someUser);

        $response = $this->post(route('discuss.post.delete', $post), [], ['Accept' => 'application/json']);
        $response->assertStatus(403);
    }
}
