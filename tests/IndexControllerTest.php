<?php

namespace Alfatron\Discussions\Tests;

use Alfatron\Discussions\Models\Thread;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class IndexControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    function list_threads()
    {
        $thread = factory(Thread::class)->create();

        $response = $this->get(route('discussions.index'));
        $response->assertStatus(200);
        $response->assertSeeText($thread->title);
        $response->assertSeeText($thread->author->name);
    }

    /**
     * @test
     */
    function pagination_links_are_displayed()
    {
        $this->markTestIncomplete();
    }
}
