<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use Simtabi\Laranail\Supports\TypeConverter;

if (!function_exists('existsInFilterKey')) {
    /**
     * Appends passed value if condition is true
     *
     * @param $key
     * @param $value
     * @return null
     */
    function existsInFilterKey($key, $value = null)
    {
        return collect(explode("&", $key))->contains($value);
    }
}

if (!function_exists('joinInFilterKey')) {
    /**
     * Appends passed value if condition is true
     *
     * @param $value
     * @return null
     */
    function joinInFilterKey(...$value)
    {
        //remove empty values
        $value = array_filter($value, function ($item) {
            return isset($item);
        });

        return collect($value)->implode('&');
    }
}

if (!function_exists('removeFromFilterKey')) {
    /**
     * Appends passed value if condition is true
     *
     * @param $key
     * @param $oldValues
     * @param $value
     * @return null
     */
    function removeFromFilterKey($key, $oldValues, $value)
    {
        $newValues = array_diff(
            array_values(
                explode("&", $oldValues)
            ), [
            $value, 'page'
        ]);

        if (count($newValues) === 0) {
            Arr::except(request()->query(), [$key, 'page']);
            return null;
        }

        return collect($newValues)->implode('&');
    }
}

if (!function_exists('isAssocArray')) {

    /**
     * Checks if an array is associative or not
     * @param array $array
     * @return boolean Returns true in a given array is associative and false if it's not
     */
    function isAssocArray(array $array): bool
    {
        if (empty($array) || !is_array($array)) {
            return false;
        }

        foreach (array_keys($array) as $key) {
            if (!is_int($key)) {
                return true;
            }
        }
        return false;
    }

}

if (!function_exists('sortItemWithChildren')) {
    /**
     * Sort parents before children
     * @param Collection|array $list
     * @param array $result
     * @param int $parent
     * @param int $depth
     * @return array
     */
    function sortItemWithChildren($list, array &$result = [], $parent = null, $depth = 0): array
    {
        if ($list instanceof Collection) {
            $listArr = [];
            foreach ($list as $item) {
                $listArr[] = $item;
            }
            $list = $listArr;
        }

        foreach ($list as $key => $object) {
            if ((int)$object->parent_id === (int)$parent) {
                array_push($result, $object);
                $object->depth = $depth;
                unset($list[$key]);
                sortItemWithChildren($list, $result, $object->id, $depth + 1);
            }
        }

        return $result;
    }
}

if (!function_exists('object2Array')) {
    function object2Array(object $object){
        return json_decode(json_encode($object), true, 512, JSON_THROW_ON_ERROR);
    }
}

if (!function_exists('array2Object')) {

    function array2Object(array $array): stdClass
    {
        $obj = new stdClass;
        foreach($array as $k => $v) {
            if(strlen($k)) {
                if(is_array($v)) {
                    $obj->{$k} = array2Object($v); //RECURSION
                } else {
                    $obj->{$k} = $v;
                }
            }
        }
        return $obj;
    }
}

if (!function_exists('json_encode_prettify')) {

    /**
     * @param array $data
     * @return string
     * @throws JsonException
     */
    function json_encode_prettify($data)
    {
        return json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}

if (!function_exists('objectToArray')) {
    function objectToArray($object){
        return json_decode(json_encode($object), true);
    }
}

if (!function_exists('arrayToObject')) {
    function arrayToObject($array){
        return json_decode(json_encode($array));
    }
}

if (!function_exists('array2JSONObj')) {
    function array2JSONObj($array){
        return json_decode(json_encode($array, JSON_FORCE_OBJECT), false);
    }
}


if (!function_exists('arrayFetch')) {

    /**
     * Get a value from an object or an array.  Allows the ability to fetch a nested value from a
     * heterogeneous multidimensional collection using dot notation.
     *
     * @param array|object $data
     * @param string       $key
     * @param mixed        $default
     * @return mixed
     */
    function arrayFetch( $key, $data, $default = null ) {
        $value = $default;
        if ( is_array( $data ) && array_key_exists( $key, $data ) ) {
            $value = $data[$key];
        } else if ( is_object( $data ) && property_exists( $data, $key ) ) {
            $value = $data->$key;
        } else {
            $segments = explode( '.', $key );
            foreach ( $segments as $segment ) {
                if ( is_array( $data ) && array_key_exists( $segment, $data ) ) {
                    $value = $data = $data[$segment];
                } else if ( is_object( $data ) && property_exists( $data, $segment ) ) {
                    $value = $data = $data->$segment;
                } else {
                    $value = $default;
                    break;
                }
            }
        }
        return $value;
    }
}


if (!function_exists('getSizableArray')) {
    /**
     * @param $array
     * @param int $size
     * @param int $offset
     * @return array|bool
     */
    function getSizableArray($array, $size = 0, $offset = 0){

        // if !array
        if (!is_array($array)){
            return false;
        }

        // count total values in array
        $count = count($array);

        // if size is less or equal to total values,
        // else we will use total count
        // if size is zero, then dont limit the output
        if($size <= $count && ($size !== 0)){
            $count = $size;
        }

        shuffle($array);
        return array_splice($array, $offset, $count);

    }
}

if (!function_exists('getRandomArrayElement')) {
    /**
     * @param $array
     * @return bool|object
     */
    function getRandomArrayElement($array)
    {
        if (is_object($array) || is_array($array)){
            if (count((array)$array) == 0){
                return false;
            }
        }
        $array = (array) $array;
        $data  = [];
        foreach ($array as $item){
            $data[] = $item;
        }
        return TypeConverter::toObject($data[array_rand($data)]);
    }
}

if (!function_exists('objIsEmpty')) {
    /**
     * @param $obj
     * @return bool|object
     */
    function objIsEmpty($obj): bool
    {
        if (! ((array)$obj)) {
            return true;
        }
        return false;
    }
}