<?php

namespace Alfatron\Discuss\Tests\ControllerTests;

use Alfatron\Discuss\Models\Category;
use Alfatron\Discuss\Models\Thread;
use Alfatron\Discuss\Tests\TestCase;
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
            route('discuss.thread.create'),
            ['title' => $invalidTitle],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    /**
     * @test
     */
    function title_should_be_unique_when_creating()
    {
        $thread = factory(Thread::class)->create();
        $this->actingAs(factory(config('discuss.user_model'))->create());

        $response = $this->post(
            route('discuss.thread.create'),
            ['title' => $thread->title],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    /**
     * @test
     */
    function validate_category_when_creating()
    {
        $this->actingAs(factory(config('discuss.user_model'))->create());

        // Required
        $response = $this->post(
            route('discuss.thread.create'),
            ['category_id' => ''],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['category_id']);

        // Exists
        $response = $this->post(
            route('discuss.thread.create'),
            ['category_id' => 13241237],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['category_id']);
    }

    /**
     * @test
     */
    function validate_body_when_creating()
    {
        $this->actingAs(factory(config('discuss.user_model'))->create());

        $response = $this->post(
            route('discuss.thread.create'),
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
            route('discuss.thread.create'),
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

    /**
     * @test
     */
    function update_successfully()
    {
        $thread = factory(Thread::class)->create();
        $this->actingAs($thread->author);

        $title    = $this->faker->text;
        $body     = $this->faker->text(5000);
        $response = $this->post(
            route('discuss.thread.update', $thread),
            [
                'title' => $title,
                'body'  => $body,
            ],
            ['Accept' => 'application/json']
        );

        $response->assertOk();
        $this->assertDatabaseHas(discuss_table('threads'), [
            'id'    => $thread->id,
            'title' => $title,
        ]);

        $response->assertExactJson([
            'success' => true,
            'title'   => $title,
            'body'    => $body,
        ]);
    }

    /**
     * @test
     */
    function category_cannot_be_changed_through_update_api()
    {
        $thread        = factory(Thread::class)->create();
        $originalCtgId = $thread->category_id;
        $this->actingAs($thread->author);

        $otherCategory = factory(Category::class)->create();

        $response = $this->post(
            route('discuss.thread.update', $thread),
            [
                'title'       => $this->faker->text,
                'body'        => $this->faker->text(5000),
                'category_id' => $otherCategory->id,
            ],
            ['Accept' => 'application/json']
        );

        $response->assertOk();

        $this->assertDatabaseHas(discuss_table('threads'), [
            'id'          => $thread->id,
            'category_id' => $originalCtgId,
        ]);

        $this->assertDatabaseMissing(discuss_table('threads'), [
            'id'          => $thread->id,
            'category_id' => $otherCategory->id,
        ]);
    }

    /**
     * @test
     * @dataProvider invalidTitles
     *
     * @param $invalidTitle
     */
    function validate_title_when_updating($invalidTitle)
    {
        $thread = factory(Thread::class)->create();
        $this->actingAs($thread->author);

        $response = $this->post(
            route('discuss.thread.update', $thread),
            ['title' => $invalidTitle],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    /**
     * @test
     */
    function title_should_be_unique_when_updating()
    {
        $thread = factory(Thread::class)->create();
        $this->actingAs($thread->author);

        $response = $this->post(
            route('discuss.thread.update', $thread),
            ['title' => $thread->title],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    /**
     * @test
     */
    function delete_successfully()
    {
        $thread = factory(Thread::class)->create();
        $this->actingAs($thread->author);

        $response = $this->post(route('discuss.thread.delete', $thread), [], ['Accept' => 'application/json']);

        $response->assertOk();
        $this->assertSoftDeleted($thread);

        $response->assertExactJson(['success' => true]);
    }

    /**
     * @test
     */
    function change_category_successfully()
    {
        $thread = factory(Thread::class)->create();

        $thread->author->isSuperAdmin = true;
        $this->actingAs($thread->author);

        $someCategory = factory(Category::class)->create();

        $this->assertNotEquals($someCategory->id, $thread->category_id);

        $response = $this->post(route('discuss.change-category', $thread), [
            'category_id' => $someCategory->id,
        ], ['Accept' => 'application/json']);

        $response->assertOk();
        $response->assertExactJson(['success' => true]);
        $this->assertDatabaseHas(discuss_table('threads'), [
            'id'          => $thread->id,
            'category_id' => $someCategory->id,
        ]);
    }

    /**
     * @test
     */
    function make_sticky()
    {
        $thread = factory(Thread::class)->create([
            'sticky' => false,
        ]);

        $thread->author->isSuperAdmin = true;
        $this->actingAs($thread->author);

        $response = $this->post(route('discuss.make-sticky', $thread), [], ['Accept' => 'application/json']);

        $response->assertOk();
        $response->assertExactJson(['success' => true]);
        $this->assertDatabaseHas(discuss_table('threads'), [
            'id'     => $thread->id,
            'sticky' => true,
        ]);
    }

    /**
     * @test
     */
    function do_nothing_while_making_sticky_if_its_already_sticky()
    {
        $thread = factory(Thread::class)->create([
            'sticky' => true,
        ]);

        $thread->author->isSuperAdmin = true;
        $this->actingAs($thread->author);

        $response = $this->post(route('discuss.make-sticky', $thread), [], ['Accept' => 'application/json']);

        $response->assertOk();
        $response->assertExactJson(['success' => true]);
        $this->assertDatabaseHas(discuss_table('threads'), [
            'id'     => $thread->id,
            'sticky' => true,
        ]);
    }

    /**
     * @test
     */
    function make_unsticky()
    {
        $thread = factory(Thread::class)->create([
            'sticky' => true,
        ]);

        $thread->author->isSuperAdmin = true;
        $this->actingAs($thread->author);

        $response = $this->post(route('discuss.make-unsticky', $thread), [], ['Accept' => 'application/json']);

        $response->assertOk();
        $response->assertExactJson(['success' => true]);
        $this->assertDatabaseHas(discuss_table('threads'), [
            'id'     => $thread->id,
            'sticky' => false,
        ]);
    }


    /**
     * @test
     */
    function do_nothing_while_making_unsticky_if_its_already_unsticky()
    {
        $thread = factory(Thread::class)->create([
            'sticky' => false,
        ]);

        $thread->author->isSuperAdmin = true;
        $this->actingAs($thread->author);

        $response = $this->post(route('discuss.make-unsticky', $thread), [], ['Accept' => 'application/json']);

        $response->assertOk();
        $response->assertExactJson(['success' => true]);
        $this->assertDatabaseHas(discuss_table('threads'), [
            'id'     => $thread->id,
            'sticky' => false,
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
