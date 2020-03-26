<?php

namespace Alfatron\Discuss\Tests;

use Alfatron\Discuss\DiscussServiceProvider;
use Alfatron\Discuss\Models\Category;
use Alfatron\Discuss\Models\FollowedThread;
use Alfatron\Discuss\Models\Post;
use Alfatron\Discuss\Models\Thread;

class ConfigTest extends TestCase
{

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
}
