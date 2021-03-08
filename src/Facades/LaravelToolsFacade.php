<?php

namespace Simtabi\Laranail\Facades;

use Illuminate\Support\Facades\Facade;
use Simtabi\Laranail\Supports\Languages;

class LaravelToolsFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return LaravelToolsFacade::class;
    }
}