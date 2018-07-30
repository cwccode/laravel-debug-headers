# Laravel Debug Headers

`cwccode/laravel-debug-headers` is a small package for Laravel 5.4+ that adds HTTP headers to responses to quickly debug queries and execution times. It is intended for Laravel applications that act only as an API.

If you're looking to debug a Laravel app that is not just an API, check out [Laravel Debugbar](https://github.com/barryvdh/laravel-debugbar) (it has a lot more to offer).

## Installation

You can install the package using composer:

```bash
composer require --dev cwccode/laravel-debug-headers
```

For Laravel 5.4, you'll need to register the service provider manually in `/config/app.php`.

## Usage

The middleware is automatically added to the `api` middleware group, but if you've renamed the group, you can enter it in your `.env` file:

```
API_MIDDLEWARE_GROUP=my-api-group
```

Now, whenever you make requests to your application's API (e.g. using postman), you'll see the following headers in the response:

```
Laravel-Queries: select * from `users` where `api_token` = 'abc' limit 1 [0.51 ms]
Laravel-Queries: select count(*) as aggregate from `posts` where `published` = 1 [2.61 ms]
Laravel-Queries: select * from `posts` where `published` = 1 limit 15 offset 0 [0.53 ms]
Laravel-Queries-Time: 3.65 ms
Laravel-Queries-Total: 3
Laravel-Time: 0.1735 s
```
