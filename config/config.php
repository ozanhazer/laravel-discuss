<?php

return [
    /**
     * Prefix for the urls
     */
    'route_prefix' => 'discuss',

    /**
     * Prefix for the database tables.
     */
    'table_prefix' => 'discuss',

    /**
     * User class for the authors of discussion posts
     */
    'user_model'   => config('auth.providers.users.model', App\User::class),

    /**
     * Name of the route for the user profile page. You can disable user profile
     * page altogether by setting this value to empty string or null.
     */
    'profile_route' => 'discuss.user',

    /**
     * The name of the middleware group in *your* Laravel application.
     * See App\Http\Kernel::$middlewareGroups
     */
    'middleware_group' => 'web',

    /**
     * The name of the auth middleware in *your* Laravel application.
     * See App\Http\Kernel::$routeMiddleware
     * Laravel discuss does not handle the authentication but uses
     * the setup in your project.
     */
    'auth_middleware' => 'auth',

    /**
     * The policy for thread moderation
     * laravel-discuss keeps the permissions in it's own database table
     * however you may add your own policy class to implement custom logic.
     */
    'thread_policy' => \Alfatron\Discuss\Policies\ThreadPolicy::class,

    /**
     * The policy for post moderation
     */
    'post_policy' => \Alfatron\Discuss\Policies\PostPolicy::class,
];
