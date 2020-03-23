<?php

return [
    /**
     * Prefix for the urls
     */
    'route_prefix' => 'discussions',

    /**
     * Prefix for the database tables.
     */
    'table_prefix' => 'discussions',

    /**
     * User class for the authors of discussion posts
     */
    'user_model'   => config('auth.providers.users.model', App\User::class),
];
