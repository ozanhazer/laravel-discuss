<?php

namespace Alfatron\Discuss\Tests\ServiceClassTests;

use Alfatron\Discuss\Models\Category;
use Alfatron\Discuss\Models\Thread;
use Alfatron\Discuss\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Route;

class BreadcrumbsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    function breadcrumbs_shown_or_hidden_for_all_routes()
    {
        $routesByName = Route::getRoutes()->getRoutesByName();
        $routeNames   = array_keys($routesByName);

        foreach ($routeNames as $routeName) {
            switch ($routeName) {
                case 'discuss.index':
                case 'discuss.permissions.list':
                    $this->get(route($routeName))
                        ->assertDontSee('class="breadcrumb"');
                    break;
                case 'discuss.detail':
                    $thread = factory(Thread::class)->create();
                    $this->get($thread->url())
                        ->assertSee('class="breadcrumb"');
                    break;
                case 'discuss.user':
                case 'discuss.permissions.edit':
                    $this->get(route($routeName, factory(config('discuss.user_model'))->create()))
                        ->assertDontSee('class="breadcrumb"');
                    break;
                case 'discuss.category':
                    $this->get(route($routeName, factory(Category::class)->create()))
                        ->assertSee('class="breadcrumb"');
                    break;
                case 'discuss.my-participation':
                case 'discuss.followed-threads':
                    $this->actingAs(factory(config('discuss.user_model'))->create());
                    $this->get(route($routeName))
                        ->assertSee('class="breadcrumb"');
                    break;
                case 'discuss.thread.populate': // xhr
                case 'discuss.post.populate': // xhr
                case 'discuss.permissions.find-user': // xhr
                    break;
                default:
                    if (in_array('GET', $routesByName[$routeName]->methods)) {
                        $this->assertTrue(false, 'Untested route: ' . $routeName);
                    }
            }
        }
    }
}
