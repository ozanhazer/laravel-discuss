<?php

namespace Alfatron\Discussions\Tests;

class IndexControllerTest extends TestCase
{

    /**
     * @test
     */
    function list_threads()
    {
        $response = $this->get(route('discussions.index'));
        $response->assertStatus(200);
    }
}
