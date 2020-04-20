<?php

namespace Alfatron\Discuss\Tests\FeatureTests;

use Alfatron\Discuss\Models\Post;
use Alfatron\Discuss\Models\Thread;
use Alfatron\Discuss\Tests\HelperClasses\User;
use Alfatron\Discuss\Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;

class DenormalizeThreadReplyInfoTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    /**
     * @test
     */
    public function last_post_fields_are_populated_on_reply()
    {
        $thread = factory(Thread::class)->create();
        $this->assertNull($thread->last_post_at);

        $post = factory(Post::class)->create(['thread_id' => $thread->id]);

        $thread->refresh();
        $this->assertNotNull($thread->last_post_at);
        $this->assertInstanceOf(Carbon::class, $thread->last_post_at);
        $this->assertEquals($post->created_at, $thread->last_post_at);
        $this->assertEquals($post->user_id, $thread->last_posted_by);
    }

    /**
     * @test
     */
    public function last_post_fields_are_updated_when_reply_is_deleted()
    {
        $thread = factory(Thread::class)->create();

        // Create posts...
        $posts = array_map(function ($day) use ($thread) {
            return factory(Post::class)->create([
                'thread_id'  => $thread->id,
                'created_at' => Carbon::now()->subMonth()->addDays($day)->setMicroseconds(0),
            ]);
        }, range(1, 4));

        // Make sure that the data is correct before starting to delete
        $thread->refresh();
        $this->assertEquals($posts[3]->created_at, $thread->last_post_at);
        $this->assertEquals($posts[3]->user_id, $thread->last_posted_by);

        // If any reply but the last is deleted the last post date won't be updated
        $posts[1]->delete();
        $thread->refresh();
        $this->assertEquals($posts[3]->created_at, $thread->last_post_at);
        $this->assertEquals($posts[3]->user_id, $thread->last_posted_by);

        // If the last reply is deleted the last post date should be updated
        Arr::last($posts)->delete();
        $thread->refresh();
        $this->assertEquals($posts[2]->created_at, $thread->last_post_at);
        $this->assertEquals($posts[2]->user_id, $thread->last_posted_by);
    }

    /**
     * @test
     */
    public function post_count_field_is_populated_on_reply()
    {
        $thread = factory(Thread::class)->create();
        $this->assertEquals(0, $thread->post_count);

        factory(Post::class)->create(['thread_id' => $thread->id]);

        $thread->refresh();
        $this->assertEquals(1, $thread->post_count);

        factory(Post::class)->create(['thread_id' => $thread->id]);

        $thread->refresh();
        $this->assertEquals(2, $thread->post_count);
    }

    /**
     * @test
     */
    public function post_count_field_is_updated_when_reply_is_deleted()
    {
        $thread = factory(Thread::class)->create();

        $posts = factory(Post::class, 5)->create(['thread_id' => $thread->id]);

        $thread->refresh();
        $this->assertEquals(5, $thread->post_count);

        $posts[0]->delete();
        $thread->refresh();
        $this->assertEquals(4, $thread->post_count);
    }
}
