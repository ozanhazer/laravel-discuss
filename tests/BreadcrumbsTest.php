<?php


namespace Alfatron\Discuss\Tests;


use Alfatron\Discuss\Models\Category;
use Alfatron\Discuss\Models\Thread;
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
        $routeNames = array_keys(Route::getRoutes()->getRoutesByName());

        foreach ($routeNames as $routeName) {
            switch ($routeName) {
                case 'discuss.index';
                    $this->get(route($routeName))
                        ->assertSee('class="breadcrumb"');
                    break;
                case 'discuss.detail';
                    $thread = factory(Thread::class)->create();
                    $this->get($thread->url())
                        ->assertSee('class="breadcrumb"');
                    break;
                case 'discuss.user';
                    $this->get(route($routeName, factory(config('discuss.user_model'))->create()))
                        ->assertDontSee('class="breadcrumb"');
                    break;
                case 'discuss.category';
                    $this->get(route($routeName, factory(Category::class)->create()))
                        ->assertSee('class="breadcrumb"');
                    break;
                default:
                    $this->assertTrue(false, 'Untested route: ' . $routeName);
            }
        }

    }
}
