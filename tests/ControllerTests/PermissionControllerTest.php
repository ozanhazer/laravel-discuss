<?php

namespace Alfatron\Discuss\Tests\ControllerTests;

use Alfatron\Discuss\Models\Permission;
use Alfatron\Discuss\Models\Post;
use Alfatron\Discuss\Models\Thread;
use Alfatron\Discuss\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;

class PermissionControllerTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

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
        $authUser               = factory(config('discuss.user_model'))->create();
        $authUser->isSuperAdmin = true;
        $this->actingAs($authUser);

        $user = factory(config('discuss.user_model'))->create();

        $permsGiven = [
            Thread::class => ['insert', 'update'],
            Post::class   => ['update'],
        ];

        foreach ($permsGiven as $entity => $abilities) {
            foreach ($abilities as $ability) {
                factory(Permission::class)->create([
                    'user_id' => $user->id,
                    'entity'  => $entity,
                    'ability' => $ability,
                ]);
            }
        }

        $this->get(route('discuss.permissions.edit', $user))
            ->assertStatus(200)
            ->assertViewHas('user', $user)
            ->assertViewHas('permissions')
            ->assertViewHas('userPermissions', $permsGiven);
    }

    /**
     * @test
     */
    public function edit_permissions_page_should_work_for_new_users()
    {
        $authUser               = factory(config('discuss.user_model'))->create();
        $authUser->isSuperAdmin = true;
        $this->actingAs($authUser);

        $user = factory(config('discuss.user_model'))->create();

        $this->get(route('discuss.permissions.edit', $user))
            ->assertStatus(200)
            ->assertViewHas('user', $user)
            ->assertViewHas('permissions')
            ->assertViewHas('userPermissions', []);
    }

    /**
     * @test
     */
    public function selected_permissions_should_be_saved()
    {
        $authUser               = factory(config('discuss.user_model'))->create();
        $authUser->isSuperAdmin = true;
        $this->actingAs($authUser);

        $user = factory(config('discuss.user_model'))->create();

        $postParams = [
            'user_id' => $user->id,
            'perms'   => [
                Thread::class => ['insert', 'update'],
                Post::class   => ['update'],
            ],
        ];

        $this->post(route('discuss.permissions.save'), $postParams, ['Accept' => 'application/json'])
            ->assertStatus(302);

        $this->assertDatabaseHas(discuss_table('permissions'), [
            'user_id' => $user->id,
            'entity'  => Thread::class,
            'ability' => 'insert',
        ]);
        $this->assertDatabaseHas(discuss_table('permissions'), [
            'user_id' => $user->id,
            'entity'  => Thread::class,
            'ability' => 'update',
        ]);
        $this->assertDatabaseHas(discuss_table('permissions'), [
            'user_id' => $user->id,
            'entity'  => Post::class,
            'ability' => 'update',
        ]);
    }

    /**
     * @test
     */
    public function unselected_permissions_should_be_deleted_when_saving()
    {
        $authUser               = factory(config('discuss.user_model'))->create();
        $authUser->isSuperAdmin = true;
        $this->actingAs($authUser);

        $user = factory(config('discuss.user_model'))->create();

        // Saved permissions...
        $permsGiven = [
            Thread::class => ['insert', 'update'],
            Post::class   => ['update', 'delete'],
        ];

        foreach ($permsGiven as $entity => $abilities) {
            foreach ($abilities as $ability) {
                factory(Permission::class)->create([
                    'user_id' => $user->id,
                    'entity'  => $entity,
                    'ability' => $ability,
                ]);
            }
        }

        // Make the request
        $postParams = [
            'user_id' => $user->id,
            'perms'   => [
                Thread::class => ['delete'],
                Post::class   => ['update'],
            ],
        ];

        $this->post(route('discuss.permissions.save'), $postParams, ['Accept' => 'application/json'])
            ->assertStatus(302);

        // Check thread...
        $this->assertDatabaseMissing(discuss_table('permissions'), [
            'user_id' => $user->id,
            'entity'  => Thread::class,
            'ability' => 'insert',
        ]);
        $this->assertDatabaseMissing(discuss_table('permissions'), [
            'user_id' => $user->id,
            'entity'  => Thread::class,
            'ability' => 'update',
        ]);
        $this->assertDatabaseHas(discuss_table('permissions'), [
            'user_id' => $user->id,
            'entity'  => Thread::class,
            'ability' => 'delete',
        ]);

        // Check post
        $this->assertDatabaseMissing(discuss_table('permissions'), [
            'user_id' => $user->id,
            'entity'  => Post::class,
            'ability' => 'delete',
        ]);
        $this->assertDatabaseHas(discuss_table('permissions'), [
            'user_id' => $user->id,
            'entity'  => Post::class,
            'ability' => 'update',
        ]);
    }

    /**
     * @test
     */
    public function all_permissions_should_be_deleted_if_none_of_them_are_selected()
    {
        $authUser               = factory(config('discuss.user_model'))->create();
        $authUser->isSuperAdmin = true;
        $this->actingAs($authUser);

        $user = factory(config('discuss.user_model'))->create();

        // Saved permissions...
        $permsGiven = [
            Thread::class => ['insert', 'delete'],
            Post::class   => ['insert', 'delete'],
        ];

        foreach ($permsGiven as $entity => $abilities) {
            foreach ($abilities as $ability) {
                factory(Permission::class)->create([
                    'user_id' => $user->id,
                    'entity'  => $entity,
                    'ability' => $ability,
                ]);
            }
        }

        // Make the request
        $postParams = [
            'user_id' => $user->id,
        ];

        $this->post(route('discuss.permissions.save'), $postParams, ['Accept' => 'application/json'])
            ->assertStatus(302);

        $this->assertDatabaseMissing(discuss_table('permissions'), [
            'user_id' => $user->id,
        ]);
    }

    /**
     * @test
     * @dataProvider invalidPerms
     *
     * @param $perms
     */
    public function validate_perms_when_saving_permissions($perms)
    {
        $authUser               = factory(config('discuss.user_model'))->create();
        $authUser->isSuperAdmin = true;
        $this->actingAs($authUser);

        $user = factory(config('discuss.user_model'))->create();

        $postParams = [
            'user_id' => $user->id,
            'perms'   => $perms,
        ];

        $response = $this->post(route('discuss.permissions.save'), $postParams, ['Accept' => 'application/json'])
            ->assertStatus(422);

        $response->assertJsonValidationErrors(['perms']);
    }

    /**
     * @test
     */
    public function find_user_with_email()
    {
        $authUser               = factory(config('discuss.user_model'))->create();
        $authUser->isSuperAdmin = true;
        $this->actingAs($authUser);

        $email = $this->faker->email;

        $user = factory(config('discuss.user_model'))->create(compact('email'));

        $this->get(route('discuss.permissions.find-user', ['user' => $email]))
            ->assertStatus(200)
            ->assertExactJson(['uri' => route('discuss.permissions.edit', $user)]);
    }

    /**
     * @test
     */
    public function validate_find_user_request()
    {
        $authUser               = factory(config('discuss.user_model'))->create();
        $authUser->isSuperAdmin = true;
        $this->actingAs($authUser);

        // User not found
        $this->get(route('discuss.permissions.find-user', ['user' => $this->faker->email]), ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['user' => 'No user found with this email address']);

        // Own account
        $this->get(route('discuss.permissions.find-user', ['user' => $authUser->email]), ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['user' => 'You cannot edit your own permissions']);

        // Super admin, can already do anything
        // FIXME: How to test this?
        $this->markTestIncomplete();
    }

    public function invalidPerms()
    {
        $perms[] = ['asdfafsd' => ['delete']];
        $perms[] = ['asdfafsd'];
        $perms[] = [0];
        $perms[] = [0 => 1];
        $perms[] = ['xxx', 'asdfafsd' => ['delete']];
        $perms[] = [Thread::class => ['asdfsaf']];
        $perms[] = [Thread::class => 'asdfsaf'];
        $perms[] = [Thread::class => 1];

        // $perms[] = [Thread::class => ['delete']];

        return array_map(function ($perms) {
            return [$perms];
        }, $perms);
    }
}
