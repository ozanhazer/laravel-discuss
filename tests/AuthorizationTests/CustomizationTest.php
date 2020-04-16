<?php

namespace Alfatron\Discuss\Tests\AuthorizationTests;

use Alfatron\Discuss\Models\Post;
use Alfatron\Discuss\Models\Thread;
use Alfatron\Discuss\Tests\models\PostPolicy;
use Alfatron\Discuss\Tests\models\ThreadPolicy;
use Alfatron\Discuss\Tests\TestCase;
use Illuminate\Support\Facades\Gate;

class CustomizationTest extends TestCase
{
    protected function useTestClassForPolicies($app)
    {
        $app->config->set('discuss.thread_policy', ThreadPolicy::class);
        $app->config->set('discuss.post_policy', PostPolicy::class);
    }

    /**
     * @test
     * @environment-setup useTestClassForPolicies
     */
    function custom_policies_can_be_set_in_the_config()
    {
        $policy = Gate::getPolicyFor(Thread::class);
        $this->assertInstanceOf(ThreadPolicy::class, $policy);

        $policy = Gate::getPolicyFor(Post::class);
        $this->assertInstanceOf(PostPolicy::class, $policy);
    }
}
