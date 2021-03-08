<?php

use Simtabi\Laranail\Supports\TypeConverter;
use Illuminate\Database\Eloquent\Model;

if (!function_exists('get_form_usersList')) {

    function get_form_usersList(Model $usersModel)
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


if (!function_exists('get_user_info')) {

    function get_user_info($request, $id, Model $usersModel){
        return get_model_info($request, $usersModel::find($id), '');
    }
}


if (!function_exists('get_user_data')) {
    function get_user_data($userId, Model $usersModel){
        return $usersModel::select('*')
            ->where('id', '=', trim($userId))
            ->orderby('id', 'desc')->get();
    }
}

if (!function_exists('get_user_data_by_username')) {
    function get_user_data_by_username($username, Model $usersModel): bool
    {
        if (!user_exists($username, $usersModel, 'username')) {
            return false;
        }
        return $usersModel::select('*')
            ->where('username', '=', trim($username))
            ->orderby('username', 'desc')->get();
    }
}


if (!function_exists('user_exists')) {
    function user_exists($value, Model $usersModel, $key = 'id'): bool
    {
        return $usersModel::where($key, '=', $value)->exists() ? true : false;
    }
}






