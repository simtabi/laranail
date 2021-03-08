<?php

namespace Simtabi\Laranail\Facades;

use Illuminate\Support\Facades\Facade;
use Simtabi\Pheg\Phegs;

class PhegsFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Phegs::class;
    }
}