<?php

namespace Alfatron\Discussions\Tests;

use Alfatron\Discussions\Models\Thread;

class IndexControllerTest extends TestCase
{

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
}
