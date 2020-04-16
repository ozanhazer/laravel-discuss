<?php

namespace Alfatron\Discuss\Tests\ControllerTests;

use Alfatron\Discuss\Models\Post;
use Alfatron\Discuss\Models\Thread;
use Alfatron\Discuss\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Route;

class MyParticipationControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    function my_participations_endpoint_works()
    {
        $this->actingAs(factory(config('discuss.user_model'))->create());

        $response = $this->get(route('discuss.my-participation'));
        $response->assertOk();
    }

    /**
     * @test
     */
    function my_participation_link_requires_authentication()
    {
        // By default laravel redirects to login if not authenticated
        Route::get('/login', function () {
        })->name('login');

        $response = $this->get(route('discuss.my-participation'));
        $response->assertRedirect();
    }

    /**
     * @test
     */
    function my_participation_page_shows_both_created_and_replied_threads()
    {
        $me = factory(config('discuss.user_model'))->create();
        $this->actingAs($me);

        $createdThreads = factory(Thread::class, 3)->create([
            'user_id' => $me->id,
        ]);

        $replies = factory(Post::class, 5)->create([
            'user_id' => $me->id,
        ]);

        $threadsByOtherPeople = factory(Thread::class, 2)->create();

        $response = $this->get(route('discuss.my-participation'));
        $response->assertOk();

        foreach ($createdThreads as $thread) {
            $response->assertSee($thread->title);
        }

        foreach ($replies as $reply) {
            $response->assertSee($reply->thread->title);
        }

        foreach ($threadsByOtherPeople as $thread) {
            $response->assertDontSee($thread->title);
        }
    }
}
