<?php

namespace Alfatron\Discuss\Tests\ControllerTests;

use Alfatron\Discuss\Models\Post;
use Alfatron\Discuss\Models\Thread;
use Alfatron\Discuss\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;

class PostControllerTest extends TestCase
{

    use DatabaseTransactions, WithFaker;


    /**
     * @test
     */
    function validate_body_while_creating()
    {
        $this->actingAs(factory(config('discuss.user_model'))->create());

        $thread = factory(Thread::class)->create();

        $response = $this->post(
            route('discuss.post.create', $thread),
            ['body' => ''],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['body']);
    }

    /**
     * @test
     */
    function create_successfully()
    {
        $this->actingAs(factory(config('discuss.user_model'))->create());

        $thread = factory(Thread::class)->create();

        $response = $this->post(
            route('discuss.post.create', $thread),
            ['body' => $this->faker->text(5000)],
            ['Accept' => 'application/json']
        );

        $response->assertOk();
        $this->assertDatabaseHas(discuss_table('posts'), compact('body'));

        $response->assertExactJson(['success' => true]);
    }

    /**
     * @test
     */
    function validate_body_while_updating()
    {
        $post = factory(Post::class)->create();
        $this->actingAs($post->author);

        $response = $this->post(
            route('discuss.post.update', $post),
            ['body' => ''],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['body']);
    }


    /**
     * @test
     */
    function update_successfully()
    {
        $post = factory(Post::class)->create();
        $this->actingAs($post->author);

        $body = $this->faker->text(5000);

        $response = $this->post(
            route('discuss.post.update', $post),
            ['body' => $body],
            ['Accept' => 'application/json']
        );

        $response->assertOk();
        $this->assertDatabaseHas(discuss_table('posts'), [
            'body' => $body,
            'id'   => $post->id,
        ]);

        $response->assertExactJson(['success' => true]);
    }

    /**
     * @test
     */
    function delete_successfully()
    {
        $post = factory(Post::class)->create();
        $this->actingAs($post->author);

        $response = $this->post(route('discuss.post.delete', $post), [], ['Accept' => 'application/json']);
        $response->assertOk();
        $response->assertExactJson(['success' => true]);
        $this->assertSoftDeleted($post);
    }
}
