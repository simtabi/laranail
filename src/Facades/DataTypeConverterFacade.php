<?php

namespace Simtabi\Laranail\Facades;

use Illuminate\Support\Facades\Facade;
use Simtabi\Laranail\Supports\Languages;
use Simtabi\Pheg\Phegs\Helpers\Components\DataTools\DataTypeConverter;

class DataTypeConverterFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return DataTypeConverter::class;
    }
}