<?php

namespace Simtabi\Laranail\Facades;

use Illuminate\Support\Facades\Facade;
use Simtabi\Laranail\Supports\Languages;

class LanguagesFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Languages::class;
    }
}
