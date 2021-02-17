<?php

use Simtabi\Patch\Support\TypeConverter as TP;
use Respect\Validation\Validator as v;

if (!function_exists('filterArray')) {
    /**
     * @param $data
     * @return null
     */
    function filterArray($data){
        $data = TP::buildArray($data);
        if (!is_array($data)){
            return null;
        }
        foreach ($data as $key => $datum){
            if (is_array($datum)){
                filterArray($datum);
            }else{
                if ( empty($datum) && strlen($datum) == 0 ) {
                    unset($data[$key]);
                }
            }
        }

        return $data;
    }
}

if (!function_exists('isArrayOrObject')) {
    /**
     * @param $value
     * @return bool
     */
    function isArrayOrObject($value) {

        if(v::arrayVal()->validate($value)){
            return true;
        }
        elseif(v::arrayType()->validate($value)){
            return true;
        }
        return false;
    }
}

if (!function_exists('isUsableArrayObject')) {
    /**
     * @param $value
     * @param bool $filter
     * @return bool
     */
    function isUsableArrayObject($value, $filter = true){
        if (!isArrayOrObject($value)){
            return false;
        }
        // remove empty values
        $value = true === $filter ? filterArray($value) : $value;

        // if array is not empty
        if (v::arrayVal()->notEmpty()->validate($value)){
            return true;
        }
        return false;
    }
}

if (!function_exists('isEmpty')) {
    /**
     * @param $value
     * @return bool
     */
    function isEmpty($value){
        // if is an array or an object
        if (isArrayOrObject($value)){
            if (isUsableArrayObject($value)){
                return true;
            }
        }
        else{
            if ( empty($value) && strlen($value) == 0 ) {
                return true;
            }
        }
        return false;
    }
}


if (!function_exists('isTimestamp')) {

/**
 * Checks if a string is a valid timestamp.
 *
 * @param  string $timestamp Timestamp to validate.
 *
 * @return bool
 */
function isTimestamp($timestamp)
{
    $check = (is_int($timestamp) OR is_float($timestamp))
        ? $timestamp
        : (string) (int) $timestamp;
    return  ($check === $timestamp)
        AND ( (int) $timestamp <=  PHP_INT_MAX)
        AND ( (int) $timestamp >= ~PHP_INT_MAX);
}
}

if (!function_exists('isTimestampAlt')) {
    /**
     * @param $timestamp
     * @return bool
     */
    function isTimestampAlt($timestamp)
    {

        if(strtotime(date('d-m-Y H:i:s',$timestamp)) === (int)$timestamp) {
            return true;
        } else return false;

    }
}

if (!function_exists('isOddNumber')) {
    /**
     * @param $number
     * @return bool
     */
    function isOddNumber($number){
        if ($number == 0){
            return false;
        }
        if ($number % 2 == 0) {
            false;
        }
        return true;
    }
}

if (!function_exists('returnIf')) {
    function returnIf($condition, $value)
    {
        if ($condition) {
            return $value;
        }
        return null;
    }
}