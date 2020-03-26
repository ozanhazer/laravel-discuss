<?php


namespace Alfatron\Discuss\Tests;


class UserControllerTest extends TestCase
{
    /**
     * @test
     */
    function user_profile_url_works()
    {
        $user = factory(config('discuss.user_model'))->create();

        $response = $this->get(route('discuss.user', $user));
        $response->assertStatus(200);
        $response->assertSeeText($user->email);
    }

    /**
     * @test
     */
    function user_profile_url_should_not_be_displayed_if_route_is_empty()
    {
        // TODO: Should not be shown on listing page
        // TODO: Profile page should return 404
        $this->markTestIncomplete();
    }
}
