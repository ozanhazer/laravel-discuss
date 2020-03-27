<?php


namespace Alfatron\Discuss\Tests;


use Alfatron\Discuss\Models\Thread;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DetailControllerTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * @test
     */
    function detail_url_works()
    {
        $thread   = factory(Thread::class)->create();
        $response = $this->get($thread->url());

        $response->assertStatus(200);
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

        $response = $this->get($url);
        $response->assertStatus(404);
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

        $response = $this->get($url);
        $response->assertStatus(404);
    }
}
