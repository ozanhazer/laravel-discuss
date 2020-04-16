<?php

namespace Alfatron\Discuss\Tests\ControllerTests;

use Alfatron\Discuss\Models\Category;
use Alfatron\Discuss\Models\Post;
use Alfatron\Discuss\Models\Thread;
use Alfatron\Discuss\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DetailControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    function detail_url_works()
    {
        $thread = factory(Thread::class)->create();
        factory(Post::class, 5)->create(['thread_id' => $thread->id]);

        $this->get($thread->url())->assertStatus(200);
    }

    /**
     * @test
     */
    function return_404_if_thread_does_not_exist()
    {
        $thread = factory(Thread::class)->create();
        $url    = $thread->url();
        $thread->forceDelete();

        $this->assertDatabaseMissing((new Thread)->getTable(), [
            'id' => $thread->id,
        ]);

        $this->get($url)->assertStatus(404);
    }

    /**
     * @test
     */
    function return_404_if_thread_is_soft_deleted()
    {
        $thread = factory(Thread::class)->create();
        $url    = $thread->url();

        $thread->delete();

        $this->assertDatabaseHas((new Thread)->getTable(), [
            'id' => $thread->id,
        ]);

        $this->get($url)->assertStatus(404);
    }

    /**
     * @test
     */
    function return_404_if_category_of_thread_was_changed()
    {
        $thread = factory(Thread::class)->create();
        $url    = $thread->url();

        $newCategory         = factory(Category::class)->create();
        $thread->category_id = $newCategory->id;
        $thread->save();

        $this->get($url)->assertStatus(404);
    }
}
