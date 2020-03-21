<?php

namespace Alfatron\Discussions\Tests;

use Alfatron\Discussions\DiscussionsServiceProvider;
use Alfatron\Discussions\Models\Category;
use Alfatron\Discussions\Models\FollowedThread;
use Alfatron\Discussions\Models\Post;
use Alfatron\Discussions\Models\Thread;

class ConfigTest extends TestCase
{

    /** @test */
    public function route_prefix_works()
    {
        $absUrl      = route('discussions.index', [], false);
        $routePrefix = '/' . config('discussions.route_prefix');

        $this->assertStringStartsWith($routePrefix, $absUrl);
    }

    /**
     * @test
     */
    function table_prefix_works()
    {
        $tablePrefix = config('discussions.table_prefix');

        $models = [
            Category::class,
            FollowedThread::class,
            Post::class,
            Thread::class,
        ];

        foreach ($models as $modelClass) {
            /** @var \Alfatron\Discussions\Models\BaseModel $model */
            $model = new $modelClass;
            $this->assertStringStartsWith($tablePrefix . '_', $model->getTable());
        }

        /** @var Category $category */
        $category = new Category();
        $this->assertEquals('discussions_categories', $category->getTable());
    }

    /**
     * @test
     */
    function no_underscore_in_the_table_name_if_table_prefix_is_empty()
    {
        config()->set('discussions.table_prefix', '');

        /** @var Category $model */
        $model = new Category();
        $this->assertEquals('categories', $model->getTable());
    }
}
