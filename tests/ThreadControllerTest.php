<?php

namespace Alfatron\Discuss\Tests;

use Alfatron\Discuss\Models\Category;
use Alfatron\Discuss\Models\Thread;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;

class ThreadControllerTest extends TestCase
{

    use DatabaseTransactions, WithFaker;

    /**
     * @test
     * @dataProvider invalidTitles
     *
     * @param $invalidTitle
     */
    function validate_title_when_creating($invalidTitle)
    {
        $this->actingAs(factory(config('discuss.user_model'))->create());

        $response = $this->post(
            route('discuss.create-thread'),
            ['title' => $invalidTitle],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    /**
     * @test
     */
    function title_should_be_unique()
    {
        $thread = factory(Thread::class)->create();
        $this->actingAs(factory(config('discuss.user_model'))->create());

        $response = $this->post(
            route('discuss.create-thread'),
            ['title' => $thread->title],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    /**
     * @test
     */
    function validate_category()
    {
        $this->actingAs(factory(config('discuss.user_model'))->create());

        // Required
        $response = $this->post(
            route('discuss.create-thread'),
            ['category_id' => ''],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['category_id']);

        // Exists
        $response = $this->post(
            route('discuss.create-thread'),
            ['category_id' => 13241237],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['category_id']);
    }

    /**
     * @test
     */
    function validate_body()
    {
        $this->actingAs(factory(config('discuss.user_model'))->create());

        $response = $this->post(
            route('discuss.create-thread'),
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

        $title    = $this->faker->text;
        $response = $this->post(
            route('discuss.create-thread'),
            [
                'title'       => $title,
                'body'        => $this->faker->text(5000),
                'category_id' => factory(Category::class)->create()->id,
            ],
            ['Accept' => 'application/json']
        );

        $response->assertOk();
        $this->assertDatabaseHas(discuss_table('threads'), compact('title'));

        $thread = Thread::query()->where('title', $title)->first();

        $response->assertExactJson([
            'success' => true,
            'url'     => $thread->url(),
        ]);
    }


    function invalidTitles()
    {
        return [
            [''],
            [null],
            ['   '],
            ['asdf 1234'], // min 10
        ];
    }
}
