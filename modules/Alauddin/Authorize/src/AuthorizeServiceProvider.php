<?php

namespace Alauddin\Authorize;

use Illuminate\Support\ServiceProvider;

class AuthorizeServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(\Illuminate\Routing\Router $router)
    {
        // Add a new middleware to beginning of the stack if it does not already exist.
        //$kernel->prependMiddleware(\path\to\custom\middleware\file::class)

        // Add a new middleware to end of the stack if it does not already exist.
        //$kernel->pushMiddleware(\path\to\custom\middleware\file::class)

        // Get or set the middlewares attached to the route.
        //$router->middleware(\path\to\custom\middleware\file::class)


        if(!$this->app->routesAreCached()){
            require __DIR__ . '/route.php';
        }

        $this->publishes(
            [__DIR__.'/config/authorization.php' => config_path('authorization.php')],
            'config'
        );

        $this->publishes([
            __DIR__ . '/assets' => public_path('vendor/authorize')
        ]);

        $this->loadViewsFrom(resource_path('resources/views'), 'authorize');
        $this->publishes([
            __DIR__ . '/views' => resource_path('views/vendor/authorize')
        ]);

        $this->loadMigrationsFrom(database_path('migrations'));
        $this->publishes([
            __DIR__ . '/migrations' => database_path('migrations')
        ]);

        $this->publishes([
            __DIR__ . '/seeds' => database_path('seeders')
        ]);

        //$this->registerMiddleware(\Illuminate\View\Middleware\ShareErrorsFromSession::class);
        $this->publishes([
            __DIR__ . '/Middleware' => app_path('Http/Middleware')
        ]);

        $router->aliasMiddleware('authorize', \Alauddin\Authorize\Middleware\CheckAuthorization::class);
    }

    /**
     * Register the Debugbar Middleware
     *
     * @param  string $middleware
     */
    protected function registerMiddleware($middleware)
    {
        $kernel = $this->app['Illuminate\Contracts\Http\Kernel'];
        $kernel->pushMiddleware($middleware);
    }
    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}