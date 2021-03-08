<?php

namespace Simtabi\Laranail\Facades;

use Simtabi\Pheg\Phegs\Libraries\DataTools\TypeConverter;
use Illuminate\Support\Facades\Facade;
use Simtabi\Laranail\Supports\Languages;

class TypeConverterFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return TypeConverter::class;
    }
}