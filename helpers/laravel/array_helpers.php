<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Arr;

if (!function_exists('exists_in_filterKey')) {
    /**
     * Appends passed value if condition is true
     *
     * @param $key
     * @param $value
     * @return null|mixed
     */
    function exists_in_filterKey($key, $value = null)
    {
        return collect(explode("&", $key))->contains($value);
    }
}

if (!function_exists('join_in_filterKey')) {
    /**
     * Appends passed value if condition is true
     *
     * @param $value
     * @return null|mixed
     */
    function join_in_filterKey(...$value)
    {
        //remove empty values
        $value = array_filter($value, function ($item) {
            return isset($item);
        });

        return collect($value)->implode('&');
    }
}

if (!function_exists('remove_from_filterKey')) {
    /**
     * Appends passed value if condition is true
     *
     * @param $key
     * @param $oldValues
     * @param $value
     * @return null|mixed
     */
    function remove_from_filterKey($key, $oldValues, $value)
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

if (!function_exists('sort_item_with_children')) {
    /**
     * Sort parents before children
     * @param Collection|array $list
     * @param array $result
     * @param int $parent
     * @param int $depth
     * @return array
     */
    function sort_item_with_children($list, array &$result = [], $parent = null, $depth = 0): array
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
                sort_item_with_children($list, $result, $object->id, $depth + 1);
            }
        }

        return $result;
    }
}
