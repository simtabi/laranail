<?php

namespace Simtabi\Laranail\Facades;

use Illuminate\Support\Facades\Facade;
use Simtabi\Laranail\Supports\SortItemsWithChildrenHelper;

class SortItemsWithChildrenHelperFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return SortItemsWithChildrenHelper::class;
    }
}