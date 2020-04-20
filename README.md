# Laravel Discuss

WORK IN PROGRESS!

[![License](https://img.shields.io/packagist/l/ozanhazer/laravel-discuss)](https://packagist.org/packages/ozanhazer/laravel-discuss)
[![Build Status](https://img.shields.io/travis/ozanhazer/laravel-discuss/master.svg)](https://travis-ci.org/ozanhazer/laravel-discuss)
[![Quality Score](https://img.shields.io/scrutinizer/g/ozanhazer/laravel-discuss.svg)](https://scrutinizer-ci.com/g/ozanhazer/laravel-discuss)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/ozanhazer/laravel-discuss.svg)](https://packagist.org/packages/ozanhazer/laravel-discuss)
[![Total Downloads](https://img.shields.io/packagist/dt/ozanhazer/laravel-discuss.svg)](https://packagist.org/packages/ozanhazer/laravel-discuss)
[![Style CI Status](https://github.styleci.io/repos/256029924/shield)](https://github.styleci.io/repos/256029924)

Laravel Discuss is a very customizable form add-on for any laravel v6 project.

## Features

* Default laravel conventions and setup is respected, works out of the box
* Fully customizable to be blended into your project completely
* Extensive tests written, full code coverage
* Well documented
* Categories
* Follow threads
* List participated threads
* Customizable user profile page
* Localization support
* Authorization and moderation support
* Number of visits for threads: counted unique, honors "Do Not Track", crawlers are ignored, proxies are taken into account.

### What is customizable?

*See the documentation for details*

1. **Design**: A very basic design built using Twitter Bootstrap and simple - easy to understand - javascript comes out of the box. 
You may publish the front-end files (blade, js, scss) and adjust them to match your project's design.

2. **URL Prefix**: You may change the url prefix for your taste or localization concerns.

3. **Table Prefix**: All tables are prefixed with `discuss`, you may change or remove the prefix.

4. **User Model**: The discussions are linked to the laravel User eloquent model. If you changed the location of the default 
   User class or prefer to use a separate model for the forum, you may do so!
   
5. **Authorization**: You may implement an `isDiscussSuperAdmin` method on your User class for moderation. Or you
   can adjust the permissions by implementing your own logic based on standard laravel authorization.

6. **User Profile Page**: A very basic user profile page is provided, which you can customize the design. 
   If you already have a profile page for your project you may use it instead, or disable forum profile 
   page alltogether.
   
## Requirements

- Laravel 6.18+
- Redis (Used for unique view counts)

## Installation

You can install the package via composer:

```bash
composer require ozanhazer/laravel-discuss
```

Run the migrations:

```bash
php artisan migrate
```

Add `isDiscussSuperAdmin` method to `App\User` class.  
(See the 'Customization' documentation if your user model is different)

and navigate to `https://yourproject.test/discuss`

Necessary tables are prefixed with `discuss_` by default. You can change or remove this
prefix through the config file. You may want to  publish the vendor files before running the
migrations if you think you'll have a conflict with the table names. 

## Customization

What you can customize:
* User avatar
* User display name
* User model class
* User profile page address (change or disable)
* Url prefix
* Database table prefix
* Middleware group
* Auth middleware
* Authorization rules:
  * Super admin
  * Thread policy
  * Post (reply) policy
* Frontend: Blade Views
* Translation files

Run `php artisan vendor:publish --provider=Alfatron\Discuss\DiscussServiceProvider` to publish 
views, config and translation files. You can also publish them separately by using the `tags` 
option like: 

`php artisan vendor:publish --provider=Alfatron\Discuss\DiscussServiceProvider --tag=config`.
`php artisan vendor:publish --provider=Alfatron\Discuss\DiscussServiceProvider --tag=views`.
`php artisan vendor:publish --provider=Alfatron\Discuss\DiscussServiceProvider --tag=lang`.

### Configuration:

 Config Key            | default          | Description
---------------------- + ---------------- + ---------------------
 route_prefix          | discuss          | Prefix for the urls
 table_prefix          | discuss          | Prefix for the database tables. Can be set as empty string.
 user_model            | App\User::class  | User class for the authors of discussion posts
 profile_route         | discuss.user     | Name of the route for user profile page 
 middleware_group      | web              | "web" is the default route group in a typical Laravel application
 auth_middleware       | auth             | "auth" is the default authentication middleware in a typical Laravel application
 thread_policy         | Alfatron\Discuss\Policies\ThreadPolicy::class | 
 post_policy           | Alfatron\Discuss\Policies\PostPolicy::class |
 view_count.honor_dnt  | true             |
 view_count.storage    | Alfatron\Discuss\Discuss\UniqueChecker\RedisStorage::class |
 view_count.expiration | 60 * 24          |
 
### Customizing the User model

A profile page for the authors of the discuss is provided. An author is an instance of
`App\User` class by default. If `User` class is moved to another namespace in your project 
or you need to use a different model you may change the `user_model` config key above.

Profile page urls are in this form by default: `https://yourproject.test/discuss/user/1`.
You may make use of `getRouteKeyName()` feature of Laravel to customize it: just add a 
public method to the `User` class and return the database field:

```php
class User exteds Authenticatable
{
    // ....

    public function getRouteKeyName()
    {
        return 'username';
    }
}
```

You can also change the `profile_route` config if you want to use a custom user profile page.
An instanse of `user_model` (i.e `User` class) will be passed as a parameter. You disable user
profile page by setting `profile_route` to empty string or `null`.

### Authorization

Laravel Discuss uses the standard Laravel authorization feature by utilizing the policies.
The permissions are kept in a separate table so no modifications are made to your own
tables and it is fully customizable.

There is a separate page provided with the package for granting permissions per user which 
you may access from the index page of laravel-discuss. However if you already have authorization 
in your project you can define your custom policies too.

To be able to moderate the discussion forums and see the permission setup page you'll need to 
describe which users should be super admin first. To do that implement `isDiscussSuperAdmin`
method on your `User` class like:

```php
class User exteds Authenticatable
{
    // ....

    public function isDiscussSuperAdmin()
    {
        return $this->isSuperAdmin();
        // or:
        // return $this->is_admin === true
        // return $this->permissions()->where('permission', 'admin')->count() > 0
    }
}
```


Available permissions are:

 Permission     | Entity
--------------- + -------
 insert         | Thread 
 update         | Thread
 delete         | Thread
 changeCategory | Thread
 makeSticky     | Thread
 insert         | Post
 update         | Post
 delete         | Post


## Screenshots

Soon...


### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.


## Credits

- [Ozan Hazer](https://github.com/ozanhazer)

Inspired by:

- [Laracasts Discuss](https://laracasts.com/discuss)
- [DevDojo Forums](https://devdojo.com/forums) and [Chatter](https://github.com/thedevdojo/chatter)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

