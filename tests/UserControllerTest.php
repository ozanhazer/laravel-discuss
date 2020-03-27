<?php


namespace Alfatron\Discuss\Tests;


use Alfatron\Discuss\Models\Thread;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserControllerTest extends TestCase
{

    use DatabaseTransactions;

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
    function user_profile_should_return_404_if_user_profiles_are_disabled()
    {
        $user = factory(config('discuss.user_model'))->create();

        config()->set('discuss.profile_route', null);
        $response = $this->get(route('discuss.user', $user));
        $response->assertStatus(404);
    }

    /**
     * @test
     */
    function users_should_not_be_linked_if_user_profiles_are_disabled()
    {
        $thread = factory(Thread::class)->create();
        $user = $thread->author;

        $profileUrl = route('discuss.user', $user);

        $response = $this->get(route('discuss.index'));
        $response->assertSee($profileUrl);

        // Let's disable user profiles and test again
        config()->set('discuss.profile_route', null);

        $response = $this->get(route('discuss.index'));
        $response->assertDontSee($profileUrl);
    }
}
