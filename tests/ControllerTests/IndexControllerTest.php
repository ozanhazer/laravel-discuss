<?php

namespace Alfatron\Discuss\Tests\ControllerTests;

use Alfatron\Discuss\Models\Category;
use Alfatron\Discuss\Models\Thread;
use Alfatron\Discuss\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class IndexControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    function list_threads()
    {
        $thread = factory(Thread::class)->create();

        $response = $this->get(route('discuss.index'));
        $response->assertOk();
        $response->assertSeeText($thread->title);
        $response->assertSeeText($thread->author->discussDisplayName());
    }

    /**
     * @test
     */
    function not_found_message_shown_if_there_are_no_messages_yet()
    {
        $response = $this->get(route('discuss.index'));
        $response->assertSeeText('No posts found');
    }

    /**
     * @test
     */
    function pagination_links_are_displayed()
    {
        factory(Thread::class, 40)->create();

        $response = $this->get(route('discuss.index'));
        $response->assertOk();
        $response->assertSee('class="pagination"');
    }

    /**
     * @test
     */
    function categories_are_listed()
    {
        // Since we want to decouple the test from the html code, we
        // create multiple pages of data and make sure that all the
        // categories are shown on the left menu.
        $this->assertEquals(0, Category::query()->count());
        $threads = factory(Thread::class, 100)->create();

        $response = $this->get(route('discuss.index'));
        $response->assertOk();

        foreach ($threads as $thread) {
            $response->assertSee(e($thread->category->name));
        }
    }

    /**
     * @test
     */
    function threads_are_filtered_by_category()
    {
        $category1 = factory(Category::class)->create();
        $category2 = factory(Category::class)->create();

        factory(Thread::class, 4)->create([
            'category_id' => $category1->id,
        ]);

        factory(Thread::class, 40)->create([
            'category_id' => $category2->id,
        ]);

        // Index page (no categories selected)
        $response = $this->get(route('discuss.index'));
        $response->assertOk();
        $this->assertEquals(44, $response->viewData('threads')->total());

        // Category 1
        $response = $this->get(route('discuss.category', $category1));
        $response->assertOk();
        $this->assertEquals(4, $response->viewData('threads')->total());

        // Category 2
        $response = $this->get(route('discuss.category', $category2));
        $response->assertOk();
        $this->assertEquals(40, $response->viewData('threads')->total());
    }
}
