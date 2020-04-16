<?php

namespace Alfatron\Discuss\Tests;

use Alfatron\Discuss\Models\Category;
use Alfatron\Discuss\Models\Thread;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;

class SlugTest extends TestCase
{
    use WithFaker, DatabaseTransactions;

    /**
     * @test
     */
    function it_should_generate_slugs_when_inserting()
    {
        $category       = new Category();
        $category->name = $this->faker->name;
        $category->save();

        $this->assertNotEmpty($category->slug);

        $thread        = factory(Thread::class)->make();
        $thread->title = $this->faker->name;
        $thread->slug  = null;
        $thread->save();

        $this->assertNotEmpty($thread->slug);
    }

    /**
     * @test
     */
    function it_should_not_change_the_slug_during_update_for_seo()
    {
        $category       = new Category();
        $category->name = $this->faker->name;
        $category->save();

        // Remember the values
        $initialName = $category->name;
        $slug        = $category->slug;

        // Update...
        $category->name = $this->faker->name;
        $category->save();

        $this->assertNotEquals($category->name, $initialName);
        $this->assertEquals($category->slug, $slug);
    }

    /**
     * @test
     */
    function if_slug_is_set_to_null_before_updating_slug_should_be_regenerated()
    {
        $category       = new Category();
        $category->name = $this->faker->name;
        $category->save();

        // Remember the values
        $initialName = $category->name;
        $slug        = $category->slug;

        // Update...
        $category->slug = null;
        $category->name = $this->faker->name;
        $category->save();

        $this->assertNotEquals($category->name, $initialName);
        $this->assertNotNull($category->slug);
        $this->assertNotEmpty($category->slug);
        $this->assertNotEquals($category->slug, $slug);
    }
}
