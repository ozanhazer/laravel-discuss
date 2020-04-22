<?php

namespace Alfatron\Discuss\Tests\ServiceClassTests;

use Alfatron\Discuss\Tests\HelperClasses\User;
use Alfatron\Discuss\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class DiscussUserTraitTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function discuss_super_admin_is_false_by_default()
    {
        $userModel = new User();
        $this->assertFalse($userModel->isDiscussSuperAdmin());
    }

    /**
     * @test
     */
    public function default_display_name_is_generated_from_email()
    {
        $userModel        = new User();
        $userModel->email = 'ozanhazer@gmail.com';

        $this->assertEquals('oz****r@g****.com', $userModel->discussDisplayName());

        $userModel->email = 'ozan@alfatron.com.tr';
        $this->assertEquals('o****@a****m.tr', $userModel->discussDisplayName());
    }

    /**
     * @test
     */
    public function default_avatar_is_gravatar()
    {
        $userModel        = new User();
        $userModel->email = $this->faker->email;

        $this->assertStringStartsWith('https://www.gravatar', $userModel->discussAvatar());
    }
}
