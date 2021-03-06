<?php

namespace Alfatron\Discuss\Tests\AuthorizationTests;

use Alfatron\Discuss\Discuss\Permissions;
use Alfatron\Discuss\Models\Permission;
use Alfatron\Discuss\Models\Thread;
use Alfatron\Discuss\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Gate;

class AuthorizationTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function should_allow_everything_if_discuss_super_admin_returns_true()
    {
        $user = factory(config('discuss.user_model'))->create();
        $this->actingAs($user);

        $permissions = Permissions::$availablePermissions;

        $user->isSuperAdmin = true;

        foreach ($permissions as $entity => $abilities) {
            foreach ($abilities as $ability) {
                $this->assertTrue(Gate::allows($ability, new $entity), json_encode([$entity, $ability]));
            }
        }
    }

    /**
     * @test
     */
    public function should_not_affect_rules_if_discuss_super_admin_returns_false()
    {
        $user = factory(config('discuss.user_model'))->create();
        $this->actingAs($user);

        foreach ([null, false, '0', 0, 'asasdf', '1', 1] as $val) {
            $user->isSuperAdmin = $val;
            $this->assertFalse(Gate::allows('edit-permissions'), 'Val: ' . $val);
            $this->assertTrue(Gate::allows('insert', new Thread()), 'Val: ' . $val);
        }
    }

    /**
     * @test
     * @dataProvider permissions
     *
     * @param $entity
     * @param $ability
     */
    public function given_permissions_work($entity, $ability)
    {
        $user     = factory(config('discuss.user_model'))->create();
        $authUser = factory(config('discuss.user_model'))->create();

        $this->assertFalse($user->can('update', new Thread()));

        $permission             = new Permission();
        $permission->user_id    = $user->id;
        $permission->ability    = $ability;
        $permission->entity     = $entity;
        $permission->granted_by = $authUser->id;
        $permission->save();

        $user->refresh();

        $this->assertTrue($user->can($ability, new $entity()));
    }

    public function permissions()
    {
        $pairs = [];
        foreach (Permissions::$availablePermissions as $entity => $abilities) {
            foreach ($abilities as $ability) {
                $pairs[] = [$entity, $ability];
            }
        }

        return $pairs;
    }
}
