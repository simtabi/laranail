<?php

if (!function_exists('modelInfo')) {
    /**
     * @param $request
     * @param $Model
     * @param string $default
     * @return null|string
     */
    function modelInfo($request, $Model, $default = ''){
        $output = null;
        if (isset($Model->$request)){
            $output = $Model->$request;
        }else if (isset($Model[$request])){
            $output = $Model[$request];
        }
        return !empty($output) ? $output : $default;
    }
}


if (!function_exists('getCleanModelArray')) {
    /**
     * @param $ModelData
     * @return bool|object
     */
    function getCleanModelArray($ModelData){
        if (is_object($ModelData) || is_array($ModelData)){
            if (count((array)$ModelData) == 0){
                return false;
            }
        }

        $data = null;
        foreach ($ModelData as $modelDatum){
            $data[] = $modelDatum;
        }
        return TypeConverter::toObject($data);
    }
}


if (!function_exists('getOneRandomModelEntry')) {
    /**
     * @param $ModelData
     * @return bool|object
     */
    function getOneRandomModelEntry($ModelData){
        return getRandomArrayElement(getCleanModelArray($ModelData));
    }
}