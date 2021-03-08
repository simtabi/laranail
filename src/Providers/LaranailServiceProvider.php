<?php

namespace Simtabi\Laranail\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Simtabi\Laranail\Commands\TidyApplicationCommand;
use Simtabi\Laranail\Traits\LaranailTrait;
use PrettyRoutes\ServiceProvider as PrettyRoutesServiceProvider;

class LaranailServiceProvider extends ServiceProvider
{
    use
        LaranailTrait;

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        if (! $this->app->configurationIsCached()) {
            foreach ([
                        'config',
                     ] as $fileName) {
                $this->mergeConfigFrom(__DIR__.'/../../config/' . $fileName . '.php', $fileName);
            }
        }

        $this->app->register(BladeServiceProvider::class);

        $path = __DIR__ . '/../../helpers/';
        $this->autoloadHelpers($path . 'cms');
        $this->autoloadHelpers($path . 'general');
        $this->autoloadHelpers($path . 'laravel');
        $this->autoloadHelpers($path . 'models');
        $this->autoloadHelpers($path . 'others');
        $this->autoloadHelpers($path . 'packages');

    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                TidyApplicationCommand::class,
            ]);
            $this->registerPublishing();
        }

        $this->registerRoutes();
        $this->app->register(PrettyRoutesServiceProvider::class);
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        $this->publishes([
            __DIR__ . '/../../resources/js/components' => resource_path('js/components/Laranail'),
        ], 'subscribers-vue-component');

        $this->publishes([
            __DIR__ . '/../../database/migrations'     => database_path('migrations'),
        ], 'subscribers-migrations');

        $this->publishes([
            __DIR__.'/../../config/config.php'         => config_path('laranail.php'),
        ], 'subscribers-config');
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Route::group($this->apiRouteConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
        });
        Route::group($this->webRouteConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        });
    }

    /**
     * Get the Subscribers route group configuration array for web middleware.
     *
     * @return array
     */
    protected function webRouteConfiguration()
    {
        return [
            'namespace'  => 'Simtabi\Laranail\Http\Controllers\Web',
            'as'         => 'laranail.',
            'prefix'     => 'laranail',
            'middleware' => 'web',
        ];
    }

    /**
     * Get the Subscribers route group configuration array for api middleware.
     *
     * @return array
     */
    protected function apiRouteConfiguration()
    {
        return [
            'namespace'  => 'Simtabi\Laranail\Http\Controllers\Api',
            'as'         => 'laranail.api.',
            'prefix'     => 'laranail-api',
            'middleware' => 'api',
        ];
    }
}
