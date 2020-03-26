<?php

namespace Alfatron\Discuss\Tests;

use Alfatron\Discuss\Models\Thread;
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

        $response = $this->get(route('discuss.index'));
        $response->assertStatus(200);
        $response->assertSeeText($thread->title);
        $response->assertSeeText($thread->author->name);
    }

    /**
     * @test
     */
    function not_found_message_shown_if_there_are_no_messages_yet()
    {
        $response = $this->get(route('discuss.index'));
        $response->assertSeeText('No posts found');
    }

    /**
     * @test
     */
    function pagination_links_are_displayed()
    {
        factory(Thread::class, 40)->create();

        $response = $this->get(route('discuss.index'));
        $response->assertStatus(200);
        $response->assertSee('class="pagination"');
    }
}
