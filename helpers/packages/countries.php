<?php

use Simtabi\Laranail\Supports\Countries;

if (!function_exists('getCountriesInList')) {
    /**
     * @param bool $alpha2
     * @return array
     */
    function getCountriesInList($alpha2 = true){
        return Countries::getCountriesInList($alpha2);
    }
}
