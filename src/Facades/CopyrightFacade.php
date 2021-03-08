<?php

namespace Simtabi\Laranail\Facades;

use Illuminate\Support\Facades\Facade;
use Simtabi\Pheg\Phegs\Copyright\Copyright;

class CopyrightFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Copyright::class;
    }
}