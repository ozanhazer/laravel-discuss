<?php

namespace Alfatron\Discuss\Tests\AuthorizationTests;

use Alfatron\Discuss\Tests\HelperClasses\SuperUser;
use Alfatron\Discuss\Tests\TestCase;

class PermissionControllerTest extends TestCase
{
    /**
     * @test
     */
    public function only_users_with_edit_permission_can_see_permissions()
    {
        $this->actingAs(factory(config('discuss.user_model'))->create());

        $response = $this->get(route('discuss.permissions.list'));
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function only_users_with_edit_permissions_can_see_permission_edit_form()
    {
        $authUser = factory(config('discuss.user_model'))->create();
        $this->actingAs($authUser);

        $user = factory(config('discuss.user_model'))->create();

        // Test for other user
        $this->get(route('discuss.permissions.edit', $user))
            ->assertStatus(403);

        // Test for own user: cannot even see own edit screen
        $this->get(route('discuss.permissions.edit', $authUser))
            ->assertStatus(403);
    }

    /**
     * @test
     */
    public function only_users_with_edit_permission_can_save_permissions()
    {
        $authUser = factory(config('discuss.user_model'))->create();
        $this->actingAs($authUser);

        $user = factory(config('discuss.user_model'))->create();

        // Test for other user
        $this->post(route('discuss.permissions.save'), ['user_id' => $user->id], ['Accept' => 'application/json'])
            ->assertStatus(403);

        // Test for own user
        $this->post(route('discuss.permissions.save'), ['user_id' => $authUser->id], ['Accept' => 'application/json'])
            ->assertStatus(403);
    }

    /**
     * @test
     */
    public function cannot_edit_own_permissions()
    {
        $authUser = factory(config('discuss.user_model'))->create();
        $authUser->isSuperAdmin = true;

        $this->actingAs($authUser);

        $user = factory(config('discuss.user_model'))->create();

        // Some other user
        $this->get(route('discuss.permissions.edit', $user))
            ->assertStatus(200);

        $this->post(route('discuss.permissions.save'), ['user_id' => $user->id])
            ->assertStatus(302);

        // Own user
        $this->get(route('discuss.permissions.edit', $authUser))
            ->assertStatus(403);

        $this->post(route('discuss.permissions.save'), ['user_id' => $authUser->id], ['Accept' => 'application/json'])
            ->assertStatus(403);
    }

    /**
     * @test
     */
    public function should_not_edit_permissions_if_user_is_super_user()
    {
        config()->set('discuss.user_model', SuperUser::class);

        $authUser = factory(config('discuss.user_model'))->create();
        $authUser->isSuperAdmin = true;

        $this->actingAs($authUser);

        $user = factory(config('discuss.user_model'))->create();

        // Can see the edit page
        $this->get(route('discuss.permissions.edit', $user))
            ->assertStatus(200);

        // ...but cannot update it
        $this->post(route('discuss.permissions.save'), ['user_id' => $user->id], ['Accept' => 'application/json'])
            ->assertStatus(403);
    }

    /**
     * @test
     */
    public function cannot_query_users_if_cannot_edit_permissions()
    {
        $this->get(route('discuss.permissions.find-user', ['user' => 'asdf']), ['Accept' => 'application/json'])
            ->assertStatus(401);
    }
}
