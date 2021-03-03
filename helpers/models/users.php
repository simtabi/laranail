<?php

use Simtabi\Laranail\Supports\TypeConverter;
use Illuminate\Database\Eloquent\Model;

if (!function_exists('getFormUsersList')) {

    function getFormUsersList(Model $usersModel)
    {
        $results = [];
        $query   = $usersModel::select('*')->orderby('id', 'asc')->get()->toArray();

        if (count($query) < 1) {
            return$results;
        }
        else{
            foreach ($query as $key => $item) {
                $username             = isset($item['username']) ? ucfirst($item['username']) : strtolower($item['email']);
                $results[$item['id']] = $username;
            }
            return $results;
        }

    }
}


if (!function_exists('getUserInfo')) {

    function getUserInfo($request, $id, Model $usersModel){
        return modelInfo($request, $usersModel::find($id), '');
    }
}


if (!function_exists('getUserData')) {
    function getUserData($userId, Model $usersModel){
        return $usersModel::select('*')
            ->where('id', '=', trim($userId))
            ->orderby('id', 'desc')->get();
    }
}

if (!function_exists('getUserDataByUsername')) {
    function getUserDataByUsername($username, Model $usersModel): bool
    {
        if (!userExists($username, $usersModel, 'username')) {
            return false;
        }
        return $usersModel::select('*')
            ->where('username', '=', trim($username))
            ->orderby('username', 'desc')->get();
    }
}


if (!function_exists('userExists')) {
    function userExists($value, Model $usersModel, $key = 'id'): bool
    {
        return $usersModel::where($key, '=', $value)->exists() ? true : false;
    }
}






