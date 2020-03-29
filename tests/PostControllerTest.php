<?php

namespace Alfatron\Discuss\Tests;

use Alfatron\Discuss\Models\Thread;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;

class PostControllerTest extends TestCase
{

    use DatabaseTransactions, WithFaker;


    /**
     * @test
     */
    function validate_body()
    {
        $this->actingAs(factory(config('discuss.user_model'))->create());

        $thread = factory(Thread::class)->create();

        $response = $this->post(
            route('discuss.create-post', $thread),
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
            route('discuss.create-post', $thread),
            ['body' => $this->faker->text(5000)],
            ['Accept' => 'application/json']
        );

        $response->assertOk();
        $this->assertDatabaseHas(discuss_table('posts'), compact('body'));

        $response->assertExactJson(['success' => true]);
    }
}
