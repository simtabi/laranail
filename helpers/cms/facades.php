<?php

use Simtabi\Laranail\Supports\PageTitle;
use Simtabi\Laranail\Facades\PageTitleFacade;
use Simtabi\Laranail\Facades\DashboardMenuFacade;
use Simtabi\Laranail\Supports\DashboardMenu;


if (!function_exists('pageTitle')) {
    /**
     * @return PageTitle
     */
    function pageTitle()
    {
        return PageTitleFacade::getFacadeRoot();
    }
}

if (!function_exists('dashboardMenu')) {
    /**
     * @return DashboardMenu
     */
    function dashboardMenu()
    {
        return DashboardMenuFacade::getFacadeRoot();
    }
}
