<?php

namespace Alfatron\Discuss\Tests\FeatureTests;

use Alfatron\Discuss\Models\Category;
use Alfatron\Discuss\Models\FollowedThread;
use Alfatron\Discuss\Models\Post;
use Alfatron\Discuss\Models\Thread;
use Alfatron\Discuss\Tests\HelperClasses\AnotherUser;
use Alfatron\Discuss\Tests\HelperClasses\PostPolicy;
use Alfatron\Discuss\Tests\HelperClasses\ThreadPolicy;
use Alfatron\Discuss\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Gate;

class ConfigTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function route_prefix_works()
    {
        $absUrl      = route('discuss.index', [], false);
        $routePrefix = '/' . config('discuss.route_prefix');

        $this->assertStringStartsWith($routePrefix, $absUrl);
    }

    /**
     * @test
     */
    function table_prefix_works()
    {
        $tablePrefix = config('discuss.table_prefix');

        $models = [
            Category::class,
            FollowedThread::class,
            Post::class,
            Thread::class,
        ];

        foreach ($models as $modelClass) {
            /** @var \Alfatron\Discuss\Models\BaseModel $model */
            $model = new $modelClass;
            $this->assertStringStartsWith($tablePrefix . '_', $model->getTable());
        }

        /** @var Category $category */
        $category = new Category();
        $this->assertEquals('discuss_categories', $category->getTable());
    }

    /**
     * @test
     */
    function no_underscore_in_the_table_name_if_table_prefix_is_empty()
    {
        config()->set('discuss.table_prefix', '');

        /** @var Category $model */
        $model = new Category();
        $this->assertEquals('categories', $model->getTable());
    }

    /**
     * @test
     */
    function table_prefix_works_correctly_on_relations()
    {
        $post = factory(Post::class)->create();
        $this->assertEquals('discuss_threads', $post->thread->getTable());
    }

    /**
     * @test
     */
    public function user_model_is_customizable()
    {
        config()->set('discuss.user_model', AnotherUser::class);
        $thread = factory(Thread::class)->create();
        $this->assertInstanceOf(AnotherUser::class, $thread->author);
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

    protected function useTestClassForPolicies($app)
    {
        $app->config->set('discuss.thread_policy', ThreadPolicy::class);
        $app->config->set('discuss.post_policy', PostPolicy::class);
    }
}
