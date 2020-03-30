# Laravel Discuss

[![Latest Version on Packagist](https://img.shields.io/packagist/v/alfatron/discuss.svg?style=flat-square)](https://packagist.org/packages/alfatron/discuss)
[![Build Status](https://img.shields.io/travis/alfatron/discuss/master.svg?style=flat-square)](https://travis-ci.org/alfatron/discuss)
[![Quality Score](https://img.shields.io/scrutinizer/g/alfatron/discuss.svg?style=flat-square)](https://scrutinizer-ci.com/g/alfatron/discuss)
[![Total Downloads](https://img.shields.io/packagist/dt/alfatron/discuss.svg?style=flat-square)](https://packagist.org/packages/alfatron/discuss)

Laravel Discuss is a very customizable form add-on for any laravel 6.0 project.

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

## Installation

You can install the package via composer:

```bash
composer require alfatron/laravel-discuss
```

Run the migrations:

```bash
php artisan migrate
```

and navigate to `https://yourproject.test/discuss`

Necessary tables are prefixed with `discuss_` by default. You can change or remove this
prefix through the config file. You may want to  publish the vendor files before running the
migrations if you think you'll have a conflict with the table names. 

## Customization

Run `php artisan vendor:publish --provider=Alfatron\Discuss\DiscussServiceProvider` to publish 
views, config and translation files. You can also publish them separately by using the `tags` 
option like: 

`php artisan vendor:publish --provider=Alfatron\Discuss\DiscussServiceProvider --tag=config`.
`php artisan vendor:publish --provider=Alfatron\Discuss\DiscussServiceProvider --tag=views`.
`php artisan vendor:publish --provider=Alfatron\Discuss\DiscussServiceProvider --tag=lang`.

### Configuration:

 Config Key    | default          | Description
-------------- + ---------------- + ---------------------
 route_prefix  | discuss          | Prefix for the urls
 table_prefix  | discuss          | Prefix for the database tables. Can be set as empty string.
 user_model    | App\User::class  | User class for the authors of discussion posts
 profile_route | discuss.user     | Name of the route for user profile page 
 
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
By default only super users can take moderation actions. You may implement `isDiscussSuperAdmin`
method to decide which users have the permission like:

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


## Screenshots

Soon...


### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.


## Credits

- [Ozan Hazer](https://github.com/alfatron)

Inspired by:

- [Laracasts Discuss](https://laracasts.com/discuss)
- [DevDojo Forums](https://devdojo.com/forums) and [Chatter](https://github.com/thedevdojo/chatter)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

