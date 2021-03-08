<?php

if (!function_exists('get_model_info')) {

    function get_model_info($request, $Model, $default = ''): string
    {
        $output = null;
        if (isset($Model->$request)){
            $output = $Model->$request;
        }else if (isset($Model[$request])){
            $output = $Model[$request];
        }
        return !empty($output) ? $output : $default;
    }
}


if (!function_exists('get_clean_model_array')) {

    function get_clean_model_array($ModelData){
        if (is_object($ModelData) || is_array($ModelData)){
            if (count((array)$ModelData) == 0){
                return false;
            }
        }

        $data = null;
        foreach ($ModelData as $modelDatum){
            $data[] = $modelDatum;
        }
        return $data;
    }
}
