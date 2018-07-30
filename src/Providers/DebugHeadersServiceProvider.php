<?php

namespace CwcCode\LaravelDebugHeaders\Providers;

use CwcCode\LaravelDebugHeaders\Contracts\DebugService as DebugServiceContract;
use CwcCode\LaravelDebugHeaders\Http\Middleware\DebugApi;
use CwcCode\LaravelDebugHeaders\Services\DebugService;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class DebugHeadersServiceProvider extends ServiceProvider
{
    /**
     * Boot the debug middleware.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        $apiGroup = env('API_MIDDLEWARE_GROUP', 'api');

        $router->pushMiddlewareToGroup($apiGroup, DebugApi::class);
    }

    /**
     * Register the debugger.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(DebugServiceContract::class, DebugService::class);
    }
}
