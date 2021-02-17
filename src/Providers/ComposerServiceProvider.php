<?php


namespace Simtabi\Laranail\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        /**
        // Share $me data with all views
        View::composer('*', function($view) {

            $view->with('me',  auth()->check() ? auth()->user() : (object) []);
            // $view->with('currentRouteName', Route::currentRouteName());
        });
        */

    }
}
