<?php

namespace Alfatron\Discuss\Tests\ControllerTests;

use Alfatron\Discuss\Tests\TestCase;
use Illuminate\Support\Facades\Route;

class MyParticipationControllerTest extends TestCase
{
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
        Route::get('/login', function () {})->name('login');

        $response = $this->get(route('discuss.my-participation'));
        $response->assertRedirect();
    }
}
