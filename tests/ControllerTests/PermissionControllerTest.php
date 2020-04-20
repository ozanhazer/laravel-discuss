<?php

namespace Alfatron\Discuss\Tests\ControllerTests;

use Alfatron\Discuss\Models\Permission;
use Alfatron\Discuss\Tests\TestCase;

class PermissionControllerTest extends TestCase
{
    /**
     * @test
     */
    public function list_only_users_with_permissions()
    {
        $user               = factory(config('discuss.user_model'))->create();
        $user->isSuperAdmin = true;
        $this->actingAs($user);

        /** @var \Alfatron\Discuss\Tests\HelperClasses\User $user1 */
        $user1      = factory(config('discuss.user_model'))->create();
        $permission = factory(Permission::class)->create();

        $this->get(route('discuss.permissions.list'))
            ->assertStatus(200)
            ->assertViewIs('discuss::permissions.index')
            ->assertViewHas('usersWithPermissions')
            ->assertDontSeeText($user1->discussDisplayName())
            ->assertSeeText($permission->user->discussDisplayName());
    }

    /**
     * @test
     */
    public function no_users_found_message_should_be_displayed_if_no_permissions_are_given()
    {
        $user               = factory(config('discuss.user_model'))->create();
        $user->isSuperAdmin = true;
        $this->actingAs($user);

        $this->get(route('discuss.permissions.list'))
            ->assertStatus(200)
            ->assertSeeText('No permissions found');
    }

    /**
     * @test
     */
    public function dont_list_super_admins()
    {
        $this->markTestIncomplete('How can this be tested?');
    }

    /**
     * @test
     */
    public function add_permission_button_should_work_somehow()
    {
        // Not implemented yet! What's the best, easy to customize approach here?
        $this->markTestIncomplete('Browser test. Kept here as a reminder');
    }

    /**
     * @test
     */
    public function edit_permission_buttons_should_work()
    {
        $this->markTestIncomplete('Browser test. Kept here as a reminder');
    }

    /**
     * @test
     */
    public function pagination_links_should_be_displayed()
    {
        $user               = factory(config('discuss.user_model'))->create();
        $user->isSuperAdmin = true;
        $this->actingAs($user);

        factory(config('discuss.user_model'), 22)
            ->create()
            ->each(function ($user) {
                factory(Permission::class)->create(['user_id' => $user->id]);
            });

        $this->get(route('discuss.permissions.list'))
            ->assertStatus(200)
            ->assertSee('class="pagination"');

        $this->get(route('discuss.permissions.list', ['page' => 2]))
            ->assertStatus(200);

        $this->get(route('discuss.permissions.list', ['page' => 3]))
            ->assertStatus(200)
            ->assertSeeText('No permissions found');
    }

    /**
     * @test
     */
    public function edit_permissions_page_should_populate_existing_permissions()
    {
        $this->markTestIncomplete();
    }

    /**
     * @test
     */
    public function permission_checkboxes_should_be_disabled_for_super_admins()
    {
        $this->markTestIncomplete();
    }

    /**
     * @test
     */
    public function edit_permissions_page_should_work_for_new_users()
    {
        $this->markTestIncomplete();
    }

    /**
     * @test
     */
    public function selected_permissions_should_be_saved()
    {
        $this->markTestIncomplete('... and unselected one should be removed too');
    }
}
