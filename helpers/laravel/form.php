<?php


if (!function_exists('oldInput')) {
    /**
     * @param $key
     * @param null $Model
     * @param null $default
     * @param bool $returnBool
     * @return bool|mixed
     */
    function oldInput($key, $Model = null, $default = null, $returnBool = false){
        // lets get what we have stored in the database,
        // if we don't have any records,
        // return old form input value
        $value = old($key, modelInfo($key, $Model, $default));
        if ($returnBool){
            if (!empty($value)){
                return true;
            }else return false;
        }
        else return $value;
    }
}

if (!function_exists('input')) {
    /**
     * @param $oldValue
     * @param $key
     * @return mixed
     */
    function input($oldValue, $key){
        $all = Input::all();
        return isset($all[$key]) ? $all[$key] : $oldValue;
    }
}

if (!function_exists('selectStatus')) {
    /**
     * @param $oldValue
     * @param $key
     * @return string
     */
    function selectStatus($oldValue, $key){
        return (old($key) === $oldValue) && !empty($oldValue) ? ' checked ' : '';
    }
}


