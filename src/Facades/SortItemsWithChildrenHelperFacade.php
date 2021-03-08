<?php

namespace Simtabi\Laranail\Facades;

use Illuminate\Support\Facades\Facade;
use Simtabi\Pheg\Helpers\Components\ArrayTools\SortItemsWithChildrenHelper;

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