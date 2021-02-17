<?php

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;

if (!function_exists('isCurrentRouteRoute')) {
    /**
     * @param $request
     * @param null $return
     * @return bool|null
     */
    function isCurrentRouteRoute($request, $return = null){
        if (Request::is($request)){
            return !empty($return) ? $return :  true;
        }
        return false;
    }
}

if (!function_exists('isEditURI')) {
    /**
     * @param $request
     * @param null $Method
     * @return bool
     */
    function isEditURI($request, $Method = null){
        $value = request()->$request;
        if (!empty($Method) && $Method->exists()){
            $Method = $Method::find($value);
            if (!count($Method) || count($Method) == 0) {
                return false;
            }
            return true;
        }
        return false;
    }
}

if (!function_exists('getParameterValue')) {
    /**
     * @param $requestKey
     * @return null|mixed
     */
    function getParameterValue($requestKey){
        $requestParameter = \request()->route()->parameters();
        $requestKey       = trim($requestKey);
        if (isset($requestParameter["$requestKey"])){
            return $requestParameter["$requestKey"];
        }
        return null;
    }
}

if (!function_exists('isRoute')) {
    /**
     * @param $request
     * @return bool
     */
    function isRoute($request){
        if ((Request::route()->getName() == Route::current()->getName()) && Route::current()->getName() == $request){
            return true;
        }else{
            return false;
        }
    }
}

if (!function_exists('getCurrentRoute')) {
    /**
     * @return object
     */
    function getCurrentRoute(){
        return TypeConverter::toObject([
            'request' => [
                'name'   => Request::route()->getName(),
                'path'   => Request::path(),
            ],
            'method'  => Request::method(),
            'action'  => Route::currentRouteAction(),
            'name'    => Route::current()->getName(),
        ]);
    }
}

if (!function_exists('isURLSegment')) {
    function isURLSegment($segment, $position = 0){
        if (Request::segment($position) === $segment){
            return true;
        }
        return false;
    }
}

if (!function_exists('getRequestValue')) {
    /**
     * @param $key
     * @return bool|mixed
     */
    function getRequestValue($key){
        $value = request()->$key;
        if (!empty($value)){
            return $value;
        }
        return false;
    }
}

if (!function_exists('isSetUrlParamValue')) {
    /**
     * @param $request
     * @param $value
     * @return bool
     */
    function isSetUrlParamValue($request, $value){
        return app('request')->input($request) === $value;
    }
}

if (!function_exists('activeURLClass')) {
    /**
     * @param $request
     * @param $value
     * @return string
     */
    function activeURLClass($request, $value){
        return isSetUrlParamValue($request, $value) ? ' active ' : '';
    }
}

if (!function_exists('requestIs')) {
    /**
     * @param $request
     * @param $class
     * @param bool $returnBool
     * @return bool|string
     */
    function requestIs($request, $class, $returnBool = false){
        if ($returnBool){
            return Request::is($request) ? true : false;
        }
        return Request::is($request) ? " $class " : "";
    }
}

if (!function_exists('isOnPage')) {

    /**
     * Check's whether request url/route matches passed link
     *
     * @param $link
     * @param string $type
     * @return bool
     */
    function isOnPage($link, $type = "name"): bool
    {
        switch ($type) {
            case "url":
                $result = ($link === request()->fullUrl());
                break;

            default:
                $result = ($link === request()->route()->getName());
        }

        return $result;
    }
}