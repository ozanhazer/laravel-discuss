<?php

namespace Alfatron\Discuss;

use Alfatron\Discuss\Commands\InstallCommand;
use Alfatron\Discuss\Discuss\Breadcrumbs;
use Alfatron\Discuss\Discuss\UniqueChecker\UniqueCheckerStorage;
use Alfatron\Discuss\Events\ThreadVisited;
use Alfatron\Discuss\Listeners\UpdateViewCount;
use Alfatron\Discuss\Models\Category;
use Alfatron\Discuss\Models\Post;
use Alfatron\Discuss\Models\Thread;
use Alfatron\Discuss\Traits\DiscussUser;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Log;

class DiscussServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadDefinitions();

        if ($this->app->runningInConsole()) {
            $this->defineFilesToBePublished();

            // Registering package commands.
            $this->commands([
                InstallCommand::class,
            ]);
        }

        $this->bindServices();
        $this->registerModelListeners();
        $this->registerPackageListeners();
        $this->setViewModels();
        $this->registerPolicies();
        $this->checkSetup();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'discuss');
    }

    private function loadDefinitions()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'discuss');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'discuss');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->loadFactoriesFrom(__DIR__ . '/../database/factories');
    }

    private function defineFilesToBePublished(): void
    {
        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('discuss.php'),
        ], 'config');

        // Publishing the views.
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/discuss'),
        ], 'views');

        // Publishing assets.
        $this->publishes([
            __DIR__ . '/../resources/themes' => public_path('vendor/discuss'),
        ], 'themes');

        // Publishing the translation files.
        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/discuss'),
        ], 'lang');
    }

    private function registerModelListeners()
    {
        Category::saving(function ($row) {
            if (!$row->slug) {
                $row->slug = Str::slug($row->name);
            }
        });

        Thread::saving(function ($row) {
            if (!$row->slug) {
                $row->slug = Str::slug($row->title);
            }
        });

        $updatePostCount = function ($post) {
            $thread = $post->thread;

            $lastPost = Post::query()
                ->where('thread_id', $thread->id)
                ->orderBy('id', 'desc')
                ->first();

            $thread->last_posted_by = optional($lastPost)->user_id;
            $thread->last_post_at   = optional($lastPost)->created_at;
            $thread->post_count     = Post::query()->where('thread_id', $thread->id)->count();
            $thread->save();
        };

        Post::saved($updatePostCount);
        Post::deleted($updatePostCount);
    }

    private function setViewModels()
    {
        $categories = function () {
            static $categories;

            if (!$categories) {
                $categories = Category::query()->orderBy('order')->get();
            }

            return $categories;
        };

        view()->composer('discuss::partials.menu', function ($view) use ($categories) {
            $view->with('categories', $categories());
        });

        view()->composer('discuss::partials.thread-create-form', function ($view) use ($categories) {
            $view->with('categories', $categories());
        });

        view()->composer('discuss::partials.change-category-form', function ($view) use ($categories) {
            $view->with('categories', $categories());
        });

        view()->composer('discuss::partials.breadcrumbs', function ($view) {
            $view->with('breadcrumbs', new Breadcrumbs());
        });
    }

    private function registerPolicies()
    {
        Gate::before(function ($user, $ability) {
            return $user->isDiscussSuperAdmin() === true ? true : null;
        });

        Gate::define('edit-permissions', function ($user) {
            return false; // Only super admins can edit permissions
        });

        Gate::policy(Thread::class, config('discuss.thread_policy'));
        Gate::policy(Post::class, config('discuss.post_policy'));
    }

    private function registerPackageListeners()
    {
        Event::listen(ThreadVisited::class, UpdateViewCount::class);
    }

    private function bindServices()
    {
        $this->app->bind(UniqueCheckerStorage::class, config('discuss.view_count.storage'));
    }

    private function checkSetup()
    {
        if (!method_exists(config('discuss.user_model'), 'discussPermissions')) {
            $msg = 'Invalid setup: ' . DiscussUser::class . ' trait is not used';
            Log::error($msg);
            abort(501, $msg);
        }
    }
}
