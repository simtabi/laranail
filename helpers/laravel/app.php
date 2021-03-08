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

if (!function_exists('platform_path')) {
    /**
     * @param string|null $path
     * @return string
     */
    function platform_path($path = null): string
    {
        return base_path('platform/' . $path);
    }
}
