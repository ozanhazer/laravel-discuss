# Laravel Discuss

[![Latest Version on Packagist](https://img.shields.io/packagist/v/alfatron/discussions.svg?style=flat-square)](https://packagist.org/packages/alfatron/discussions)
[![Build Status](https://img.shields.io/travis/alfatron/discussions/master.svg?style=flat-square)](https://travis-ci.org/alfatron/discussions)
[![Quality Score](https://img.shields.io/scrutinizer/g/alfatron/discussions.svg?style=flat-square)](https://scrutinizer-ci.com/g/alfatron/discussions)
[![Total Downloads](https://img.shields.io/packagist/dt/alfatron/discussions.svg?style=flat-square)](https://packagist.org/packages/alfatron/discussions)

Add basic forum support to any laravel 6.0 project.

## Features

* Default laravel conventions and setup is respected, works out of the box
* Fully customizable to be blended into your project easily
* Tests written
* Categories
* Followed threads
* Participated threads
* User profile page
* Localization

A very basic design built using Twitter Bootstrap comes out of the box. You may publish the
blade files and adjust to match your project's design.


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

Run `php artisan vendor:publish` to publish views, config and translation files. You can also
publish them separately by using the `tags` option like: `php artisan vendor:publish --tag=config`.

### Configuration:

 Config Key    | default          | Description
-------------- + ---------------- + ---------------------
 route_prefix  | discuss          | Prefix for the urls
 table_prefix  | discuss          | Prefix for the database tables. Can be set as empty string.
 user_model    | App\User::class  | User class for the authors of discussion posts
 profile_route | discussions.user | Name of the route for user profile page 
 
### Customizing the User model

A profile page for the authors of the discussions is provided. An author is an instance of
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

## Screenshots

Soon...


### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.


## Credits

- [Ozan Hazer](https://github.com/alfatron)

Inspired by:

- [Laracasts Discussions](https://laracasts.com/discuss)
- [DevDojo Forums](https://devdojo.com/forums) and [Chatter](https://github.com/thedevdojo/chatter)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

