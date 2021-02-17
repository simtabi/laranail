<?php

if (!function_exists('appUrl')) {
    /**
     * @param bool $echo
     * @return string
     */
    function appUrl($echo = false){
        $url = rtrim(env('APP_URL'), '/').'/';
        if ($echo){
            echo $url;
        }else {
            return $url;
        }
    }
}