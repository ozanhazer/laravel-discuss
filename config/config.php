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
     * The name of the middleware group in your laravel application.
     */
    'middleware_group' => 'web',

    /**
     * The name of the auth middleware in your laravel application.
     */
    'auth_middleware' => 'auth',
];
