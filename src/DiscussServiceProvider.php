<?php

namespace Alfatron\Discuss;

use Alfatron\Discuss\Discuss\Breadcrumbs;
use Alfatron\Discuss\Models\Category;
use Alfatron\Discuss\Models\Post;
use Alfatron\Discuss\Models\Thread;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class DiscussServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadDefinitions();

        if ($this->app->runningInConsole()) {
            $this->definePublishedFiles();

            // Registering package commands.
            // $this->commands([]);
        }

        $this->registerModelListeners();

        $this->setViewModels();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'discuss');
    }

    private function loadDefinitions(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'discuss');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'discuss');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->loadFactoriesFrom(__DIR__ . '/../database/factories');
    }

    private function definePublishedFiles(): void
    {
        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('discuss.php'),
        ], 'config');

        // Publishing the views.
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/discuss'),
        ], 'views');

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/discuss'),
        ], 'assets');*/

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

            $thread->post_count = Post::query()->where('thread_id', $thread->id)->count();
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

        view()->composer('discuss::partials.thread-form', function ($view) use ($categories) {
            $view->with('categories', $categories());
        });

        view()->composer('discuss::partials.breadcrumbs', function ($view) {
            $view->with('breadcrumbs', new Breadcrumbs());
        });
    }
}
