<?php

if (!function_exists('getUploadsLink')) {
    /**
     * @param string $request
     * @return string
     */
    function getUploadsUrl($request = ''){
        $request = !empty($request) ? "$request" : '';
        return appUrl() . "uploads/" . $request;
    }
}

if (!function_exists('getAssetsLink')) {
    /**
     * @param $request
     * @param bool $appendAssets
     * @param bool $appendUrl
     * @return string
     */
    function getAssetsLink($request, $appendAssets = false, $appendUrl = false){
        return ($appendUrl ? appUrl() : '') . ($appendAssets ? "/assets/" : '/') . "$request";
    }
}


if (!function_exists('getUploadsLink')) {
    /**
     * @param string $request
     * @return string
     */
    function getUploadsLink($request = ''){
        return appUrl() . "uploads/" . !empty($request) ? "$request" : '';
    }
}
